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
            // Récupérer les critères depuis le POST JSON
            $input = json_decode(file_get_contents('php://input'), true);
            $criteres = $input['criteres'] ?? ['chronologique']; // Par défaut chronologique
            
            // Validation: au moins un critère
            if (empty($criteres)) {
                Flight::json([
                    'success' => false,
                    'message' => 'Au moins un critère doit être sélectionné.'
                ]);
                return;
            }
            
            $besoins = $besoinModel->getBesoinsNonSatisfaits();
            $dons = $donModel->getDonsDisponibles();
            
            $distributions_prevues = $this->calculerDistributions($besoins, $dons, $criteres);
            
            // Stocker en session pour validation ultérieure
            session_start();
            $_SESSION['simulation_distributions'] = $distributions_prevues;
            $_SESSION['simulation_criteres'] = $criteres;
            $_SESSION['simulation_date'] = date('Y-m-d');
            
            Flight::json([
                'success' => true,
                'message' => count($distributions_prevues) . ' distributions prévues (non encore validées)',
                'distributions' => $distributions_prevues,
                'criteres' => $criteres,
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
     * + Assigner les villes aux dons distribués
     */
    public function validerSimulation() {
        $db = Flight::db();
        $distributionModel = new Distribution($db);
        $besoinModel = new Besoin($db);
        $donModel = new Don($db);
        
        try {
            // Récupérer les critères de la session
            session_start();
            $criteres = $_SESSION['simulation_criteres'] ?? ['chronologique'];
            
            // Recalculer les distributions (fresh data)
            $besoins = $besoinModel->getBesoinsNonSatisfaits();
            $dons = $donModel->getDonsDisponibles();
            
            $distributions = $this->calculerDistributions($besoins, $dons, $criteres);
            $date_distribution = date('Y-m-d');
            
            $db->beginTransaction();
            
            // Grouper les distributions par don pour assigner la ville
            $dons_villes = [];
            
            foreach ($distributions as $dist) {
                // Créer la distribution
                $distributionModel->create(
                    $dist['id_besoin'],
                    $dist['id_don'],
                    $dist['quantite'],
                    $date_distribution
                );
                
                // Mémoriser la ville pour ce don (première distribution = ville assignée)
                if (!isset($dons_villes[$dist['id_don']])) {
                    $dons_villes[$dist['id_don']] = $dist['id_ville_besoin'];
                }
            }
            
            // Assigner les villes aux dons qui ont été distribués
            foreach ($dons_villes as $id_don => $id_ville) {
                $stmt = $db->prepare("UPDATE DONS SET id_ville = ? WHERE id_don = ?");
                $stmt->execute([$id_ville, $id_don]);
            }
            
            $db->commit();
            
            // Vider la session
            if (session_status() === PHP_SESSION_ACTIVE) {
                unset($_SESSION['simulation_distributions']);
                unset($_SESSION['simulation_criteres']);
            }
            
            Flight::json([
                'success' => true,
                'message' => count($distributions) . ' distributions validées et enregistrées ! ' . count($dons_villes) . ' dons assignés à des villes.',
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
     * Algorithme de distribution avec critères personnalisés
     * Priorité ABSOLUE: Correspondance nom puis type
     * Puis appliquer les critères cochés
     */
    private function calculerDistributions($besoins, $dons, $criteres = ['chronologique']) {
        $distributions = [];
        $villes_servies_ce_tour = [];
        
        // Calculer les totaux pour la proportionnalité
        $total_dons = 0;
        foreach ($dons as $don) {
            $total_dons += $don['quantite_disponible'];
        }
        
        $somme_besoins = 0;
        foreach ($besoins as $besoin) {
            $somme_besoins += $besoin['quantite_restante'];
        }
        
        // Si proportionnalité, calculer les quantités avec reste
        $quantites_proportionnelles = [];
        if (in_array('proportionnalite', $criteres) && $somme_besoins > 0) {
            $reste_total = 0;
            foreach ($besoins as $idx => $besoin) {
                $quantite_exacte = ($besoin['quantite_restante'] * $total_dons) / $somme_besoins;
                $quantite_floor = floor($quantite_exacte);
                $decimale = $quantite_exacte - $quantite_floor;
                
                $quantites_proportionnelles[$idx] = [
                    'quantite_base' => $quantite_floor,
                    'decimale' => $decimale,
                    'quantite_exacte' => $quantite_exacte
                ];
                
                $reste_total += ($quantite_exacte - $quantite_floor);
            }
            
            // Distribuer le reste aux besoins avec les plus grandes décimales
            $reste_a_distribuer = round($reste_total);
            if ($reste_a_distribuer > 0) {
                // Trier par décimale décroissante
                $indices_tries = array_keys($quantites_proportionnelles);
                usort($indices_tries, function($a, $b) use ($quantites_proportionnelles) {
                    return $quantites_proportionnelles[$b]['decimale'] <=> $quantites_proportionnelles[$a]['decimale'];
                });
                
                // Ajouter +1 aux premiers
                for ($i = 0; $i < min($reste_a_distribuer, count($indices_tries)); $i++) {
                    $idx = $indices_tries[$i];
                    $quantites_proportionnelles[$idx]['quantite_base'] += 1;
                }
            }
        }
        
        // Appliquer les critères de tri aux besoins
        $besoins = $this->trierBesoinsParCriteres($besoins, $criteres, $total_dons, $somme_besoins);
        
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
            
            // Chercher le meilleur besoin selon priorités
            $besoin_idx = $this->trouverMeilleurBesoin($besoins, $don, $villes_servies_ce_tour);
            
            // Si pas trouvé avec ville non servie, réinitialiser le tour
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
                $besoin_idx = $this->trouverMeilleurBesoin($besoins, $don, $villes_servies_ce_tour);
            }
            
            if ($besoin_idx === null) {
                $index_don++;
                continue;
            }
            
            $besoin = $besoins[$besoin_idx];
            
            // Calculer la quantité à distribuer
            $quantite = min($besoin['quantite_restante'], $don['quantite_disponible']);
            
            // Si proportionnalité activée, utiliser la quantité calculée (avec reste redistribué)
            if (in_array('proportionnalite', $criteres) && isset($quantites_proportionnelles[$besoin_idx])) {
                $quantite_proportionnelle = $quantites_proportionnelles[$besoin_idx]['quantite_base'];
                $quantite = min($quantite, $quantite_proportionnelle, $don['quantite_disponible']);
            }
            
            if ($quantite > 0) {
                $distributions[] = [
                    'id_besoin' => $besoin['id_besoin'],
                    'id_don' => $don['id_don'],
                    'id_ville_besoin' => $besoin['id_ville'],
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
     * Trier les besoins selon les critères sélectionnés
     */
    private function trierBesoinsParCriteres($besoins, $criteres, $total_dons, $somme_besoins) {
        usort($besoins, function($a, $b) use ($criteres, $total_dons, $somme_besoins) {
            // 1. Chronologique (si coché)
            if (in_array('chronologique', $criteres)) {
                $date_cmp = strcmp($a['date_creation'], $b['date_creation']);
                if ($date_cmp !== 0) return $date_cmp;
            }
            
            // 2. Petititude (si coché)
            if (in_array('petititude', $criteres)) {
                $petit_cmp = $a['quantite_restante'] <=> $b['quantite_restante'];
                if ($petit_cmp !== 0) return $petit_cmp;
            }
            
            // 3. Proportionnalité (si coché)
            if (in_array('proportionnalite', $criteres) && $somme_besoins > 0) {
                $prop_a = floor(($a['quantite_restante'] * $total_dons) / $somme_besoins);
                $prop_b = floor(($b['quantite_restante'] * $total_dons) / $somme_besoins);
                $prop_cmp = $prop_b <=> $prop_a; // Inversé: plus grand en premier
                if ($prop_cmp !== 0) return $prop_cmp;
            }
            
            return 0;
        });
        
        return $besoins;
    }
    
    /**
     * Trouver le meilleur besoin selon priorités:
     * 1. Date + quantité (déjà trié)
     * 2. Correspondance nom
     * 3. Correspondance catégorie
     * 4. Aléatoire
     */
    private function trouverMeilleurBesoin($besoins, $don, $villes_servies) {
        $candidats_nom = [];
        $candidats_categorie = [];
        $candidats_autres = [];
        
        foreach ($besoins as $idx => $besoin) {
            if (!$besoin || !isset($besoin['quantite_restante']) || $besoin['quantite_restante'] <= 0) continue;
            if (in_array($besoin['id_ville'], $villes_servies)) continue;
            
            // 3. Correspondance nom (ex: Riz → Riz)
            if (isset($besoin['demande']) && isset($don['demande']) && 
                strcasecmp(trim($besoin['demande']), trim($don['demande'])) === 0) {
                $candidats_nom[] = $idx;
            }
            // 4. Correspondance catégorie
            elseif ($besoin['id_type_besoin'] == $don['id_type_besoin']) {
                $candidats_categorie[] = $idx;
            }
            // 5. Autres
            else {
                $candidats_autres[] = $idx;
            }
        }
        
        // Retourner dans l'ordre de priorité (déjà triés par date+quantité)
        if (!empty($candidats_nom)) return $candidats_nom[0];
        if (!empty($candidats_categorie)) return $candidats_categorie[0];
        if (!empty($candidats_autres)) return $candidats_autres[0];
        
        return null;
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
     * + Remettre id_ville = NULL pour tous les dons
     */
    public function resetDistributions() {
        $db = Flight::db();
        
        try {
            $db->beginTransaction();
            
            // Supprimer toutes les distributions
            $stmt = $db->prepare("DELETE FROM DISTRIBUTIONS");
            $stmt->execute();
            
            // Remettre tous les dons à id_ville = NULL (pas de ville assignée)
            $stmt = $db->prepare("UPDATE DONS SET id_ville = NULL");
            $stmt->execute();
            
            $db->commit();
            
            Flight::json([
                'success' => true,
                'message' => 'Toutes les distributions ont été supprimées et les dons réinitialisés (villes retirées).'
            ]);
            
        } catch (\Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la réinitialisation: ' . $e->getMessage()
            ], 500);
        }
    }
}
