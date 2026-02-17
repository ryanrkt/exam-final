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
     * Exécuter la simulation de distribution automatique
     * 
     * Logique de distribution équitable par tour :
     * 1. Récupérer tous les besoins non satisfaits (triés par date_creation ASC)
     * 2. Récupérer tous les dons disponibles (triés par date_don ASC)
     * 3. Distribution par tour : chaque VILLE reçoit UN SEUL don par tour
     * 4. Une fois toutes les villes servies, on recommence un nouveau tour
     */
    public function executeSimulation() {
        $db = Flight::db();
        
        $besoinModel = new Besoin($db);
        $donModel = new Don($db);
        $distributionModel = new Distribution($db);
        
        try {
            // Récupérer les besoins non satisfaits (triés par ordre chronologique)
            $besoins = $besoinModel->getBesoinsNonSatisfaits();
            
            // Récupérer les dons disponibles
            $dons = $donModel->getDonsDisponibles();
            
            $distributions_effectuees = [];
            $date_distribution = date('Y-m-d');
            
            // Garder trace des villes servies dans le tour actuel
            $villes_servies_ce_tour = [];
            
            // Index du don actuel
            $index_don_actuel = 0;
            
            // Continuer tant qu'il reste des dons et des besoins
            while ($index_don_actuel < count($dons)) {
                $distribution_effectuee = false;
                
                // Chercher un don disponible
                $don_trouve_idx = null;
                for ($i = $index_don_actuel; $i < count($dons); $i++) {
                    if ($dons[$i] && isset($dons[$i]['quantite_disponible']) && $dons[$i]['quantite_disponible'] > 0) {
                        $don_trouve_idx = $i;
                        break;
                    }
                }
                
                // Si aucun don disponible, arrêter
                if ($don_trouve_idx === null) {
                    break;
                }
                
                $don_actuel = $dons[$don_trouve_idx];
                
                // Chercher un besoin d'une ville non encore servie dans ce tour
                $besoin_trouve_idx = null;
                
                // 1. D'abord chercher un besoin du même type d'une ville non servie
                foreach ($besoins as $idx => $besoin) {
                    if (!$besoin || !isset($besoin['quantite_restante']) || $besoin['quantite_restante'] <= 0) {
                        continue;
                    }
                    
                    // Vérifier si cette ville n'a pas encore été servie dans ce tour
                    if (in_array($besoin['id_ville'], $villes_servies_ce_tour)) {
                        continue;
                    }
                    
                    // Priorité au même type de besoin
                    if ($besoin['id_type_besoin'] == $don_actuel['id_type_besoin']) {
                        $besoin_trouve_idx = $idx;
                        break;
                    }
                }
                
                // 2. Si pas trouvé, chercher n'importe quel besoin d'une ville non servie
                if ($besoin_trouve_idx === null) {
                    foreach ($besoins as $idx => $besoin) {
                        if (!$besoin || !isset($besoin['quantite_restante']) || $besoin['quantite_restante'] <= 0) {
                            continue;
                        }
                        
                        // Vérifier si cette ville n'a pas encore été servie dans ce tour
                        if (in_array($besoin['id_ville'], $villes_servies_ce_tour)) {
                            continue;
                        }
                        
                        $besoin_trouve_idx = $idx;
                        break;
                    }
                }
                
                // Si aucun besoin trouvé pour une ville non servie, réinitialiser le tour
                if ($besoin_trouve_idx === null) {
                    // Vérifier s'il reste encore des besoins non satisfaits
                    $besoins_restants = false;
                    foreach ($besoins as $b) {
                        if ($b && isset($b['quantite_restante']) && $b['quantite_restante'] > 0) {
                            $besoins_restants = true;
                            break;
                        }
                    }
                    
                    if (!$besoins_restants) {
                        break; // Plus de besoins, arrêter
                    }
                    
                    // Réinitialiser le tour et recommencer
                    $villes_servies_ce_tour = [];
                    
                    // Chercher à nouveau un besoin
                    foreach ($besoins as $idx => $besoin) {
                        if ($besoin && isset($besoin['quantite_restante']) && $besoin['quantite_restante'] > 0) {
                            $besoin_trouve_idx = $idx;
                            break;
                        }
                    }
                }
                
                // Si toujours pas de besoin trouvé, arrêter
                if ($besoin_trouve_idx === null) {
                    break;
                }
                
                $besoin_actuel = $besoins[$besoin_trouve_idx];
                
                // Calculer la quantité à distribuer
                $quantite_a_distribuer = min($besoin_actuel['quantite_restante'], $don_actuel['quantite_disponible']);
                
                if ($quantite_a_distribuer > 0) {
                    // Créer la distribution
                    $distributionModel->create(
                        $besoin_actuel['id_besoin'],
                        $don_actuel['id_don'],
                        $quantite_a_distribuer,
                        $date_distribution
                    );
                    
                    // Mettre à jour les quantités
                    $dons[$don_trouve_idx]['quantite_disponible'] -= $quantite_a_distribuer;
                    $besoins[$besoin_trouve_idx]['quantite_restante'] -= $quantite_a_distribuer;
                    
                    // Marquer la ville comme servie dans ce tour
                    $villes_servies_ce_tour[] = $besoin_actuel['id_ville'];
                    
                    // Enregistrer la distribution effectuée
                    $distributions_effectuees[] = [
                        'besoin' => $besoin_actuel['type_besoin'],
                        'ville_besoin' => $besoin_actuel['nom_ville'],
                        'region_besoin' => $besoin_actuel['nom_region'],
                        'quantite' => $quantite_a_distribuer,
                        'don' => $don_actuel['type_besoin'],
                        'don_demande' => $don_actuel['demande'] ?? '',
                        'date_besoin' => $besoin_actuel['date_creation'],
                        'date_don' => $don_actuel['date_don']
                    ];
                    
                    $distribution_effectuee = true;
                    
                    // Si le don est épuisé, passer au don suivant
                    if ($dons[$don_trouve_idx]['quantite_disponible'] <= 0) {
                        $index_don_actuel = $don_trouve_idx + 1;
                    }
                }
                
                // Si aucune distribution effectuée, avancer
                if (!$distribution_effectuee) {
                    $index_don_actuel++;
                }
            }
            
            Flight::json([
                'success' => true,
                'message' => count($distributions_effectuees) . ' distributions effectuées',
                'distributions' => $distributions_effectuees,
                'date' => $date_distribution
            ]);
            
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la simulation: ' . $e->getMessage()
            ], 500);
        }
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
            
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'historique: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Réinitialiser toutes les distributions pour recommencer la simulation
     */
    public function resetDistributions() {
        $db = Flight::db();
        
        try {
            $stmt = $db->prepare("DELETE FROM DISTRIBUTIONS");
            $stmt->execute();
            
            Flight::json([
                'success' => true,
                'message' => 'Toutes les distributions ont été supprimées. Vous pouvez relancer la simulation.'
            ]);
            
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la réinitialisation: ' . $e->getMessage()
            ], 500);
        }
    }
}
