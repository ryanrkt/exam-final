<?php
namespace app\models;
use PDO;

class Besoin {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function create($id_ville, $id_type_besoin, $quantite, $prix_unitaire) {
        $stmt = $this->db->prepare("
            INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$id_ville, $id_type_besoin, $quantite, $prix_unitaire]);
    }
    
    public function getAll() {
        $stmt = $this->db->query("
            SELECT b.*, v.nom_ville, r.nom_region, t.libelle as type_besoin,
                   (b.quantite * b.prix_unitaire) as montant_total
            FROM BESOINS b
            JOIN VILLES v ON b.id_ville = v.id_ville
            JOIN REGION r ON v.id_region = r.id_region
            JOIN TYPE_BESOIN t ON b.id_type_besoin = t.id_type_besoin
            ORDER BY b.date_creation ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByVille($id_ville) {
        $stmt = $this->db->prepare("
            SELECT b.*, t.libelle as type_besoin,
                   (b.quantite * b.prix_unitaire) as montant_total
            FROM BESOINS b
            JOIN TYPE_BESOIN t ON b.id_type_besoin = t.id_type_besoin
            WHERE b.id_ville = ?
            ORDER BY b.date_creation ASC
        ");
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT b.*, v.nom_ville, r.nom_region, t.libelle as type_besoin,
                   (b.quantite * b.prix_unitaire) as montant_total
            FROM BESOINS b
            JOIN VILLES v ON b.id_ville = v.id_ville
            JOIN REGION r ON v.id_region = r.id_region
            JOIN TYPE_BESOIN t ON b.id_type_besoin = t.id_type_besoin
            WHERE b.id_besoin = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $id_ville, $id_type_besoin, $quantite, $prix_unitaire) {
        $stmt = $this->db->prepare("
            UPDATE BESOINS 
            SET id_ville = ?, id_type_besoin = ?, quantite = ?, prix_unitaire = ?
            WHERE id_besoin = ?
        ");
        return $stmt->execute([$id_ville, $id_type_besoin, $quantite, $prix_unitaire, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM BESOINS WHERE id_besoin = ?");
        return $stmt->execute([$id]);
    }
}