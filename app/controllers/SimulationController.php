<?php
namespace app\controllers;

use app\models\Besoin;
use app\models\Don;
use Flight;

class SimulationController
{
    /**
     * Affiche la page de simulation
     */
    public function index()
    {
        Flight::render('simulation/index');
    }

    /**
     * Lance la simulation de distribution des dons
     */
    public function run()
    {
        try {
            $db = Flight::db();
            
            // Récupérer tous les besoins non satisfaits
            $stmtBesoins = $db->query("
                SELECT b.*, v.nom_ville
                FROM BESOINS b
                JOIN VILLES v ON b.id_ville = v.id_ville
                WHERE b.statut = 'en attente'
                ORDER BY b.date_creation ASC
            ");
            $besoins = $stmtBesoins->fetchAll(\PDO::FETCH_ASSOC);
            
            // Récupérer tous les dons non attribués
            $stmtDons = $db->query("
                SELECT d.*, v.nom_ville
                FROM DONS d
                JOIN VILLES v ON d.id_ville = v.id_ville
                WHERE d.statut = 'disponible'
                ORDER BY d.date_don ASC
            ");
            $dons = $stmtDons->fetchAll(\PDO::FETCH_ASSOC);
            
            $affectations = [];
            $message = "Simulation terminée";
            
            // Algorithme simple de matching
            foreach ($besoins as $besoin) {
                $montantRestant = $besoin['quantite'] * $besoin['prix_unitaire'];
                
                foreach ($dons as &$don) {
                    if ($don['statut'] !== 'disponible') continue;
                    
                    if ($don['montant'] >= $montantRestant) {
                        // Le don peut satisfaire le besoin
                        $affectations[] = [
                            'besoin' => $besoin['id_besoin'],
                            'don' => $don['id_don'],
                            'montant_affecte' => $montantRestant,
                            'date_affectation' => date('Y-m-d H:i:s')
                        ];
                        
                        // Mettre à jour les statuts
                        $updateBesoin = $db->prepare("UPDATE BESOINS SET statut = 'satisfait' WHERE id_besoin = ?");
                        $updateBesoin->execute([$besoin['id_besoin']]);
                        
                        // Mettre à jour le don
                        $donRestant = $don['montant'] - $montantRestant;
                        if ($donRestant <= 0) {
                            $updateDon = $db->prepare("UPDATE DONS SET statut = 'utilise' WHERE id_don = ?");
                            $updateDon->execute([$don['id_don']]);
                            $don['statut'] = 'utilise';
                        } else {
                            $updateDon = $db->prepare("UPDATE DONS SET montant = ? WHERE id_don = ?");
                            $updateDon->execute([$donRestant, $don['id_don']]);
                            $don['montant'] = $donRestant;
                        }
                        
                        $montantRestant = 0;
                        break;
                    }
                }
            }
            
            Flight::json([
                'success' => true,
                'message' => $message,
                'affectations' => count($affectations),
                'details' => $affectations
            ]);
            
        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la simulation: ' . $e->getMessage()
            ], 500);
        }
    }
}
