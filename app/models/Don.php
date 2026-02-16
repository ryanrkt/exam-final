<?php
namespace app\models;
use PDO;

class Don {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function create($id_ville, $id_type_besoin, $quantite, $montant, $date_don) {
        $stmt = $this->db->prepare("
            INSERT INTO DONS (id_ville, id_type_besoin, quantite, montant, date_don) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$id_ville, $id_type_besoin, $quantite, $montant, $date_don]);
    }
    
    public function getAll() {
        $stmt = $this->db->query("
            SELECT d.*, v.nom_ville, r.nom_region, t.libelle as type_besoin
            FROM DONS d
            JOIN VILLES v ON d.id_ville = v.id_ville
            JOIN REGION r ON v.id_region = r.id_region
            JOIN TYPE_BESOIN t ON d.id_type_besoin = t.id_type_besoin
            ORDER BY d.date_don ASC, d.id_don ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}