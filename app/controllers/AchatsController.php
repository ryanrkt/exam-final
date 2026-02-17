<?php
namespace app\controllers;

use app\models\Achat;
use app\models\Besoin;
use app\models\Config;
use Flight;

class AchatsController {
    
    public function besoinsRestants() {
        $db = Flight::db();
        
        // Récupérer les besoins non satisfaits (hors "Argent")
        $stmt = $db->query("
            SELECT 
                b.*,
                v.nom_ville,
                r.nom_region,
                t.libelle as type_besoin,
                (b.quantite * b.prix_unitaire) as montant_total,
                COALESCE(SUM(dist.quantite_attribuee), 0) as quantite_satisfaite,
                (b.quantite - COALESCE(SUM(dist.quantite_attribuee), 0)) as quantite_restante
            FROM BESOINS b
            JOIN VILLES v ON b.id_ville = v.id_ville
            JOIN REGION r ON v.id_region = r.id_region
            JOIN TYPE_BESOIN t ON b.id_type_besoin = t.id_type_besoin
            LEFT JOIN DISTRIBUTIONS dist ON b.id_besoin = dist.id_besoin
            WHERE t.libelle != 'Argent'
            GROUP BY b.id_besoin
            HAVING quantite_restante > 0
            ORDER BY b.date_creation ASC
        ");
        $besoins_restants = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Récupérer les dons en argent disponibles (montant > 0)
        $stmt = $db->query("
            SELECT 
                d.*,
                v.nom_ville,
                r.nom_region
            FROM DONS d
            JOIN VILLES v ON d.id_ville = v.id_ville
            JOIN REGION r ON v.id_region = r.id_region
            JOIN TYPE_BESOIN t ON d.id_type_besoin = t.id_type_besoin
            WHERE t.libelle = 'Argent' AND d.montant > 0
            ORDER BY d.date_don ASC
        ");
        $dons_argent = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Récupérer le pourcentage de frais
        $configModel = new Config($db);
        $frais_pourcentage = $configModel->getFraisAchatPourcentage();
        
        // DEBUG
        error_log("Besoins restants: " . count($besoins_restants));
        error_log("Dons argent: " . count($dons_argent));
        error_log("Frais: " . $frais_pourcentage);
        
        // Render la vue
        Flight::render('achats/besoins_restants', [
            'besoins_restants' => $besoins_restants,
            'dons_argent' => $dons_argent,
            'frais_pourcentage' => $frais_pourcentage
        ]);
    }
}