<?php
namespace app\controllers;

use Flight;
use PDO;

class DashboardController {
    
    public function index() {
        $db = Flight::db();
        
        $filter_region = isset($_GET['region']) && $_GET['region'] !== '' ? (int)$_GET['region'] : null;
        $filter_ville = isset($_GET['ville']) && $_GET['ville'] !== '' ? (int)$_GET['ville'] : null;
        
        $regions = $this->getRegions($db);
        
        $villes = $this->getVilles($db, $filter_region);
        
        $villes_data = $this->getVillesData($db, $filter_region, $filter_ville);
        
        $stats = $this->calculateStats($villes_data);
        
        Flight::render('dashboard/index', [
            'regions' => $regions,
            'villes' => $villes,
            'villes_data' => $villes_data,
            'stats' => $stats
        ]);
    }
    

    private function getRegions($db) {
        $stmt = $db->query("SELECT * FROM REGION ORDER BY nom_region");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
 
    private function getVilles($db, $filter_region = null) {
        $sql = "SELECT v.*, r.nom_region 
                FROM VILLES v 
                LEFT JOIN REGION r ON v.id_region = r.id_region";
        
        if ($filter_region !== null) {
            $sql .= " WHERE v.id_region = :region";
        }
        
        $sql .= " ORDER BY v.nom_ville";
        
        $stmt = $db->prepare($sql);
        
        if ($filter_region !== null) {
            $stmt->bindParam(':region', $filter_region, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getCategories($db) {
        $stmt = $db->query("SELECT * FROM TYPE_BESOIN ORDER BY libelle");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getVillesData($db, $filter_region = null, $filter_ville = null, $filter_categorie = null) {
        $where_conditions = [];
        $params = [];
        
        if ($filter_region !== null) {
            $where_conditions[] = "v.id_region = :region";
            $params[':region'] = $filter_region;
        }
        
        if ($filter_ville !== null) {
            $where_conditions[] = "v.id_ville = :ville";
            $params[':ville'] = $filter_ville;
        }
        if ($filter_categorie !== null) {
            $where_conditions[] = "t.id_type_besoin = :categorie";
            $params[':categorie'] = $filter_categorie;
        }
        
        $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
        $sql = "
            SELECT 
                v.id_ville,
                v.nom_ville,
                r.nom_region,
                b.id_besoin,
                b.quantite,
                b.prix_unitaire,
                b.date_creation,
                t.libelle as type_besoin,
                COALESCE(
                    (SELECT SUM(d2.quantite) 
                     FROM DISTRIBUTIONS dist2 
                     JOIN DONS d2 ON dist2.id_don = d2.id_don 
                     WHERE dist2.id_besoin = b.id_besoin), 0
                ) as quantite_dons_recus,
                COALESCE(
                    (SELECT SUM(d2.montant) 
                     FROM DISTRIBUTIONS dist2 
                     JOIN DONS d2 ON dist2.id_don = d2.id_don 
                     WHERE dist2.id_besoin = b.id_besoin), 0
                ) as montant_dons_recus
            FROM VILLES v
            LEFT JOIN REGION r ON v.id_region = r.id_region
            LEFT JOIN BESOINS b ON v.id_ville = b.id_ville
            LEFT JOIN TYPE_BESOIN t ON b.id_type_besoin = t.id_type_besoin
            {$where_clause}
            ORDER BY r.nom_region, v.nom_ville, b.date_creation
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        

        $villes_data = [];
        
        foreach ($data as $row) {
            $ville_id = $row['id_ville'];
            
            if (!isset($villes_data[$ville_id])) {
                $villes_data[$ville_id] = [
                    'id_ville' => $row['id_ville'],
                    'nom_ville' => $row['nom_ville'],
                    'nom_region' => $row['nom_region'],
                    'besoins' => [],
                    'dons_recus' => [],
                    'total_besoins' => 0,
                    'total_dons' => 0,
                    'total_reste' => 0
                ];
            }
            
            if ($row['id_besoin']) {
                $montant_besoin = $row['quantite'] * $row['prix_unitaire'];
                $montant_dons = $row['montant_dons_recus'];
                $reste = $montant_besoin - $montant_dons;
                
                $villes_data[$ville_id]['besoins'][] = $row;
                $villes_data[$ville_id]['total_besoins'] += $montant_besoin;
                $villes_data[$ville_id]['total_dons'] += $montant_dons;
                $villes_data[$ville_id]['total_reste'] += $reste;
            }
        }
        
  
        $sql_dons = "
            SELECT 
                v.id_ville,
                t.libelle as type_besoin,
                d.quantite,
                d.montant,
                d.date_don
            FROM DONS d
            JOIN VILLES v ON d.id_ville = v.id_ville
            LEFT JOIN TYPE_BESOIN t ON d.id_type_besoin = t.id_type_besoin
            {$where_clause}
            ORDER BY d.date_don DESC, d.id_don ASC
        ";
        
        $stmt_dons = $db->prepare($sql_dons);
        $stmt_dons->execute($params);
        $dons = $stmt_dons->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($dons as $don) {
            $ville_id = $don['id_ville'];
            if (isset($villes_data[$ville_id])) {
                $villes_data[$ville_id]['dons_recus'][] = $don;
            }
        }
        
        return array_values($villes_data);
    }
    

    private function calculateStats($villes_data) {
        $stats = [
            'nb_villes' => count($villes_data),
            'total_besoins' => 0,
            'total_dons' => 0,
            'total_reste' => 0
        ];
        
        foreach ($villes_data as $ville) {
            $stats['total_besoins'] += $ville['total_besoins'];
            $stats['total_dons'] += $ville['total_dons'];
            $stats['total_reste'] += $ville['total_reste'];
        }
        
        return $stats;
    }
}