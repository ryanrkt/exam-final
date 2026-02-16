<?php
namespace app\models;
use PDO;

class Ville {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll() {
        $stmt = $this->db->query("
            SELECT v.*, r.nom_region 
            FROM VILLES v 
            LEFT JOIN REGION r ON v.id_region = r.id_region 
            ORDER BY v.nom_ville
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByRegion($id_region) {
        $stmt = $this->db->prepare("SELECT * FROM VILLES WHERE id_region = ?");
        $stmt->execute([$id_region]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}