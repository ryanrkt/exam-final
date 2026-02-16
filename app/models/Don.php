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
            LEFT JOIN VILLES v ON d.id_ville = v.id_ville
            LEFT JOIN REGION r ON v.id_region = r.id_region
            JOIN TYPE_BESOIN t ON d.id_type_besoin = t.id_type_besoin
            ORDER BY d.date_don DESC, d.id_don DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    public function update($id, $id_ville, $id_type_besoin, $quantite, $montant, $date_don) {
        $stmt = $this->db->prepare("
            UPDATE DONS 
            SET id_ville = ?, id_type_besoin = ?, quantite = ?, montant = ?, date_don = ?
            WHERE id_don = ?
        ");
        return $stmt->execute([$id_ville, $id_type_besoin, $quantite, $montant, $date_don, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM DONS WHERE id_don = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Récupérer tous les dons avec leur quantité disponible (non utilisée)
     * Triés par ordre chronologique (date_don ASC)
     */
    public function getDonsDisponibles() {
        $stmt = $this->db->query("
            SELECT d.*, 
                   v.nom_ville,
                   r.nom_region,
                   t.libelle as type_besoin,
                   d.quantite as quantite_totale,
                   COALESCE(SUM(dist.quantite_attribuee), 0) as quantite_utilisee,
                   (d.quantite - COALESCE(SUM(dist.quantite_attribuee), 0)) as quantite_disponible
            FROM DONS d
            LEFT JOIN VILLES v ON d.id_ville = v.id_ville
            LEFT JOIN REGION r ON v.id_region = r.id_region
            JOIN TYPE_BESOIN t ON d.id_type_besoin = t.id_type_besoin
            LEFT JOIN DISTRIBUTIONS dist ON d.id_don = dist.id_don
            GROUP BY d.id_don
            HAVING quantite_disponible > 0
            ORDER BY d.date_don ASC, d.id_don ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupérer un don spécifique
     */
    public function getById($id_don) {
        $stmt = $this->db->prepare("
            SELECT d.*, 
                   v.nom_ville,
                   r.nom_region,
                   t.libelle as type_besoin
            FROM DONS d
            LEFT JOIN VILLES v ON d.id_ville = v.id_ville
            LEFT JOIN REGION r ON v.id_region = r.id_region
            JOIN TYPE_BESOIN t ON d.id_type_besoin = t.id_type_besoin
            WHERE d.id_don = ?
        ");
        $stmt->execute([$id_don]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
