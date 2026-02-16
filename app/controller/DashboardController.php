<?php

class DashboardController {

    public function index() {
        $db = Flight::db();
        
        // Récupérer toutes les villes avec leurs besoins et dons
        $stmt = $db->query("
            SELECT 
                v.id_ville,
                v.nom_ville,
                r.nom_region,
                COUNT(DISTINCT b.id_besoin) as nb_besoins,
                COUNT(DISTINCT d.id_don) as nb_dons,
                COALESCE(SUM(b.quantite * b.prix_unitaire), 0) as total_besoins,
                COALESCE(SUM(d.montant), 0) as total_dons
            FROM VILLES v
            LEFT JOIN REGION r ON v.id_region = r.id_region
            LEFT JOIN BESOINS b ON v.id_ville = b.id_ville
            LEFT JOIN DONS d ON v.id_ville = d.id_ville
            GROUP BY v.id_ville, v.nom_ville, r.nom_region
            ORDER BY r.nom_region, v.nom_ville
        ");
        
        $dashboard = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Passer les données au template
        Flight::render('dashboard/index', ['dashboard' => $dashboard]);
    }
}
