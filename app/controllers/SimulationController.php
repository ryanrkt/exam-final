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
     * Logique:
     * 1. Récupérer tous les besoins non satisfaits (triés par date_creation ASC - ordre chronologique)
     * 2. Récupérer tous les dons disponibles (triés par date_don ASC)
     * 3. Pour chaque besoin (dans l'ordre chronologique):
     *    - Chercher n'importe quel don disponible (peu importe le type)
     *    - La ville qui a demandé en premier reçoit le don en priorité
     * 4. Créer une distribution avec la quantité appropriée
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
            
            // Pour chaque besoin (dans l'ordre chronologique)
            foreach ($besoins as $besoin) {
                $quantite_besoin_restant = $besoin['quantite_restante'];
                
                // Chercher n'importe quel don disponible
                foreach ($dons as &$don) {
                    // Vérifier si le don a de la quantité disponible
                    if ($don['quantite_disponible'] <= 0) {
                        continue; // Pas de quantité disponible
                    }
                    
                    // Calculer la quantité à distribuer
                    $quantite_a_distribuer = min($quantite_besoin_restant, $don['quantite_disponible']);
                    
                    if ($quantite_a_distribuer > 0) {
                        // Créer la distribution
                        $distributionModel->create(
                            $besoin['id_besoin'],
                            $don['id_don'],
                            $quantite_a_distribuer,
                            $date_distribution
                        );
                        
                        // Mettre à jour les quantités
                        $don['quantite_disponible'] -= $quantite_a_distribuer;
                        $quantite_besoin_restant -= $quantite_a_distribuer;
                        
                        // Enregistrer la distribution effectuée
                        $distributions_effectuees[] = [
                            'besoin' => $besoin['type_besoin'],
                            'ville_besoin' => $besoin['nom_ville'],
                            'quantite' => $quantite_a_distribuer,
                            'don' => $don['type_besoin'],
                            'ville_don' => $don['nom_ville'] ?? 'Non assignée',
                            'date_besoin' => $besoin['date_creation'],
                            'date_don' => $don['date_don']
                        ];
                        
                        // Si le besoin est complètement satisfait, passer au suivant
                        if ($quantite_besoin_restant <= 0) {
                            break;
                        }
                    }
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
