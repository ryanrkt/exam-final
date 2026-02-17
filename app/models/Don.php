<?php
namespace app\models;
use PDO;

class Don {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll() {
        $stmt = $this->db->query("
            SELECT 
                d.*,
                v.nom_ville,
                r.nom_region,
                t.libelle as type_besoin
            FROM DONS d
            LEFT JOIN VILLES v ON d.id_ville = v.id_ville
            LEFT JOIN REGION r ON v.id_region = r.id_region
            LEFT JOIN TYPE_BESOIN t ON d.id_type_besoin = t.id_type_besoin
            ORDER BY d.date_don DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM DONS WHERE id_don = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($id_ville, $demande, $id_type_besoin, $quantite, $montant, $date_don) {
        $stmt = $this->db->prepare("
            INSERT INTO DONS (id_ville, demande, id_type_besoin, quantite, montant, date_don)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$id_ville, $demande, $id_type_besoin, $quantite, $montant, $date_don]);
    }
    
    public function update($id, $id_ville, $demande, $id_type_besoin, $quantite, $montant, $date_don) {
        $stmt = $this->db->prepare("
            UPDATE DONS 
            SET id_ville = ?, demande = ?, id_type_besoin = ?, quantite = ?, montant = ?, date_don = ?
            WHERE id_don = ?
        ");
        return $stmt->execute([$id_ville, $demande, $id_type_besoin, $quantite, $montant, $date_don, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM DONS WHERE id_don = ?");
        return $stmt->execute([$id]);
    }
}