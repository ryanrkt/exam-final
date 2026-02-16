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
    
    /**
     * Récupérer tous les besoins avec leur quantité restante (non satisfaite)
     * Triés par ordre chronologique (date_creation ASC)
     */
    public function getBesoinsNonSatisfaits() {
        $stmt = $this->db->query("
            SELECT b.*, 
                   v.nom_ville,
                   r.nom_region,
                   c.nom_categorie,
                   b.quantite as quantite_demandee,
                   COALESCE(SUM(d.quantite_attribuee), 0) as quantite_distribuee,
                   (b.quantite - COALESCE(SUM(d.quantite_attribuee), 0)) as quantite_restante
            FROM BESOINS b
            JOIN VILLES v ON b.id_ville = v.id_ville
            JOIN REGION r ON v.id_region = r.id_region
            JOIN CATEGORIE_BESOIN c ON b.id_categorie = c.id_categorie
            LEFT JOIN DISTRIBUTIONS d ON b.id_besoin = d.id_besoin
            GROUP BY b.id_besoin
            HAVING quantite_restante > 0
            ORDER BY b.date_creation ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupérer un besoin spécifique
     */
    public function getById($id_besoin) {
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   v.nom_ville,
                   r.nom_region,
                   c.nom_categorie
            FROM BESOINS b
            JOIN VILLES v ON b.id_ville = v.id_ville
            JOIN REGION r ON v.id_region = r.id_region
            JOIN CATEGORIE_BESOIN c ON b.id_categorie = c.id_categorie
            WHERE b.id_besoin = ?
        ");
        $stmt->execute([$id_besoin]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}