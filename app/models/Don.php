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
                d.id_don,
                d.demande,
                d.id_ville,
                d.id_type_besoin,
                d.quantite,
                d.montant,
                d.date_don,
                t.libelle as type_besoin,
                v.nom_ville,
                r.nom_region,
                COALESCE((SELECT SUM(dist.quantite_attribuee) FROM DISTRIBUTIONS dist WHERE dist.id_don = d.id_don), 0) as quantite_distribuee
            FROM DONS d
            LEFT JOIN TYPE_BESOIN t ON d.id_type_besoin = t.id_type_besoin
            LEFT JOIN VILLES v ON d.id_ville = v.id_ville
            LEFT JOIN REGION r ON v.id_region = r.id_region
            ORDER BY d.date_don DESC, d.id_don DESC
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
    
    /**
     * Récupère les dons qui ont encore de la quantité disponible
     * Trie par date de don (FIFO - First In First Out)
     */
    public function getDonsDisponibles() {
        $stmt = $this->db->query("
            SELECT 
                d.*,
                v.nom_ville,
                r.nom_region,
                t.libelle as type_besoin,
                COALESCE(SUM(dist.quantite_attribuee), 0) as quantite_distribuee,
                (d.quantite - COALESCE(SUM(dist.quantite_attribuee), 0)) as quantite_disponible
            FROM DONS d
            LEFT JOIN VILLES v ON d.id_ville = v.id_ville
            LEFT JOIN REGION r ON v.id_region = r.id_region
            JOIN TYPE_BESOIN t ON d.id_type_besoin = t.id_type_besoin
            LEFT JOIN DISTRIBUTIONS dist ON d.id_don = dist.id_don
            GROUP BY d.id_don, d.id_ville, d.demande, d.id_type_besoin, d.quantite, d.montant, d.date_don,
                     v.nom_ville, r.nom_region, t.libelle
            HAVING quantite_disponible > 0
            ORDER BY d.date_don ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}