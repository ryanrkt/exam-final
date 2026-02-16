<?php
namespace app\models;
use PDO;

class TypeBesoin {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll() {
        $stmt = $this->db->query("
            SELECT * FROM TYPE_BESOIN
            ORDER BY libelle
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM TYPE_BESOIN WHERE id_type_besoin = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
