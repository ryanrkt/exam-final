<?php
namespace app\models;
use PDO;

class Distribution {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Créer une nouvelle distribution
     */
    public function create($id_besoin, $id_don, $quantite_attribuee, $date_distribution) {
        $stmt = $this->db->prepare("
            INSERT INTO DISTRIBUTIONS (id_besoin, id_don, quantite_attribuee, date_distribution) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$id_besoin, $id_don, $quantite_attribuee, $date_distribution]);
    }
    
    /**
     * Récupérer toutes les distributions
     */
    public function getAll() {
        $stmt = $this->db->query("
            SELECT d.*, 
                   b.quantite as besoin_quantite,
                   tb.libelle as besoin_type,
                   v1.nom_ville as ville_besoin,
                   don.quantite as don_quantite,
                   td.libelle as don_type,
                   v2.nom_ville as ville_don
            FROM DISTRIBUTIONS d
            JOIN BESOINS b ON d.id_besoin = b.id_besoin
            JOIN VILLES v1 ON b.id_ville = v1.id_ville
            JOIN TYPE_BESOIN tb ON b.id_type_besoin = tb.id_type_besoin
            JOIN DONS don ON d.id_don = don.id_don
            LEFT JOIN VILLES v2 ON don.id_ville = v2.id_ville
            JOIN TYPE_BESOIN td ON don.id_type_besoin = td.id_type_besoin
            ORDER BY d.date_distribution DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Calculer la quantité totale déjà distribuée pour un besoin
     */
    public function getQuantiteDistribuee($id_besoin) {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(quantite_attribuee), 0) as total
            FROM DISTRIBUTIONS
            WHERE id_besoin = ?
        ");
        $stmt->execute([$id_besoin]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    /**
     * Calculer la quantité totale déjà utilisée d'un don
     */
    public function getQuantiteUtilisee($id_don) {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(quantite_attribuee), 0) as total
            FROM DISTRIBUTIONS
            WHERE id_don = ?
        ");
        $stmt->execute([$id_don]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
