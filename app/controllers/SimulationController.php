<?php
namespace app\controllers;

use app\models\Besoin;
use app\models\Don;
use app\models\Distribution;
use Flight;

class SimulationController {
    
    /**
     * Afficher la page de simulation
     */
    public function index() {
        Flight::render('simulation/index');
    }
    
    /**
     * Exécuter la simulation de distribution (PREVIEW - ne commit pas en BD)
     * Retourne le résultat en JSON pour affichage
     */
    public function executeSimulation() {
        $db = Flight::db();
        
        $besoinModel = new Besoin($db);
        $donModel = new Don($db);
        
        try {
            $besoins = $besoinModel->getBesoinsNonSatisfaits();
            $dons = $donModel->getDonsDisponibles();
            
            $distributions_prevues = $this->calculerDistributions($besoins, $dons);
            
            // Stocker en session pour validation ultérieure
            session_start();
            $_SESSION['simulation_distributions'] = $distributions_prevues;
            $_SESSION['simulation_date'] = date('Y-m-d');
            
            Flight::json([
                'success' => true,
                'message' => count($distributions_prevues) . ' distributions prévues (non encore validées)',
                'distributions' => $distributions_prevues,
                'date' => date('Y-m-d'),
                'preview' => true
            ]);
            
        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la simulation: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Valider et enregistrer les distributions simulées en BD
     */
    public function validerSimulation() {
        $db = Flight::db();
        $distributionModel = new Distribution($db);
        $besoinModel = new Besoin($db);
        $donModel = new Don($db);
        
        try {
            // Recalculer les distributions (fresh data)
            $besoins = $besoinModel->getBesoinsNonSatisfaits();
            $dons = $donModel->getDonsDisponibles();
            
            $distributions = $this->calculerDistributions($besoins, $dons);
            $date_distribution = date('Y-m-d');
            
            $db->beginTransaction();
            
            foreach ($distributions as $dist) {
                $distributionModel->create(
                    $dist['id_besoin'],
                    $dist['id_don'],
                    $dist['quantite'],
                    $date_distribution
                );
            }
            
            $db->commit();
            
            // Vider la session
            if (session_status() === PHP_SESSION_ACTIVE) {
                unset($_SESSION['simulation_distributions']);
            }
            
            Flight::json([
                'success' => true,
                'message' => count($distributions) . ' distributions validées et enregistrées !',
                'distributions' => $distributions,
                'date' => $date_distribution
            ]);
            
        } catch (\Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la validation: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Algorithme de distribution équitable par tour
     */
    private function calculerDistributions($besoins, $dons) {
        $distributions = [];
        $villes_servies_ce_tour = [];
        $index_don = 0;
        
        while ($index_don < count($dons)) {
            // Chercher un don disponible
            $don_idx = null;
            for ($i = $index_don; $i < count($dons); $i++) {
                if ($dons[$i] && isset($dons[$i]['quantite_disponible']) && $dons[$i]['quantite_disponible'] > 0) {
                    $don_idx = $i;
                    break;
                }
            }
            
            if ($don_idx === null) break;
            
            $don = $dons[$don_idx];
            
            // Chercher besoin même type, ville non servie
            $besoin_idx = null;
            foreach ($besoins as $idx => $besoin) {
                if (!$besoin || !isset($besoin['quantite_restante']) || $besoin['quantite_restante'] <= 0) continue;
                if (in_array($besoin['id_ville'], $villes_servies_ce_tour)) continue;
                if ($besoin['id_type_besoin'] == $don['id_type_besoin']) {
                    $besoin_idx = $idx;
                    break;
                }
            }
            
            // Sinon n'importe quel besoin d'une ville non servie
            if ($besoin_idx === null) {
                foreach ($besoins as $idx => $besoin) {
                    if (!$besoin || !isset($besoin['quantite_restante']) || $besoin['quantite_restante'] <= 0) continue;
                    if (in_array($besoin['id_ville'], $villes_servies_ce_tour)) continue;
                    $besoin_idx = $idx;
                    break;
                }
            }
            
            // Si pas trouvé, réinitialiser le tour
            if ($besoin_idx === null) {
                $has_remaining = false;
                foreach ($besoins as $b) {
                    if ($b && isset($b['quantite_restante']) && $b['quantite_restante'] > 0) {
                        $has_remaining = true;
                        break;
                    }
                }
                
                if (!$has_remaining) break;
                
                $villes_servies_ce_tour = [];
                
                foreach ($besoins as $idx => $besoin) {
                    if ($besoin && isset($besoin['quantite_restante']) && $besoin['quantite_restante'] > 0) {
                        $besoin_idx = $idx;
                        break;
                    }
                }
            }
            
            if ($besoin_idx === null) break;
            
            $besoin = $besoins[$besoin_idx];
            $quantite = min($besoin['quantite_restante'], $don['quantite_disponible']);
            
            if ($quantite > 0) {
                $distributions[] = [
                    'id_besoin' => $besoin['id_besoin'],
                    'id_don' => $don['id_don'],
                    'besoin' => $besoin['type_besoin'],
                    'besoin_demande' => $besoin['demande'] ?? '',
                    'ville_besoin' => $besoin['nom_ville'],
                    'region_besoin' => $besoin['nom_region'],
                    'quantite' => $quantite,
                    'don' => $don['type_besoin'],
                    'don_demande' => $don['demande'] ?? '',
                    'date_besoin' => $besoin['date_creation'],
                    'date_don' => $don['date_don']
                ];
                
                $dons[$don_idx]['quantite_disponible'] -= $quantite;
                $besoins[$besoin_idx]['quantite_restante'] -= $quantite;
                $villes_servies_ce_tour[] = $besoin['id_ville'];
                
                if ($dons[$don_idx]['quantite_disponible'] <= 0) {
                    $index_don = $don_idx + 1;
                }
            } else {
                $index_don++;
            }
        }
        
        return $distributions;
    }
    
    /**
     * Récupérer l'historique des distributions
     */
    public function getHistorique() {
        $db = Flight::db();
        $distributionModel = new Distribution($db);
        
        try {
            $distributions = $distributionModel->getAll();
            
            Flight::json([
                'success' => true,
                'distributions' => $distributions
            ]);
            
        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'historique: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Réinitialiser toutes les distributions
     */
    public function resetDistributions() {
        $db = Flight::db();
        
        try {
            $stmt = $db->prepare("DELETE FROM DISTRIBUTIONS");
            $stmt->execute();
            
            Flight::json([
                'success' => true,
                'message' => 'Toutes les distributions ont été supprimées.'
            ]);
            
        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la réinitialisation: ' . $e->getMessage()
            ], 500);
        }
    }
}
