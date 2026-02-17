<?php
namespace app\models;
use PDO;

class Achat {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Vérifier si un achat existe déjà pour un besoin donné
     */
    public function achatExiste($id_besoin, $est_valide = null) {
        $sql = "SELECT COUNT(*) as nb FROM ACHATS WHERE id_besoin = ?";
        $params = [$id_besoin];
        
        if ($est_valide !== null) {
            $sql .= " AND est_valide = ?";
            $params[] = $est_valide ? 1 : 0;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nb'] > 0;
    }
    
    /**
     * Créer un achat (simulation ou réel)
     */
    public function creer($id_don_argent, $id_besoin, $quantite, $montant_unitaire, $frais_pourcentage, $est_simule = true) {
        $montant_total = $quantite * $montant_unitaire * (1 + $frais_pourcentage / 100);
        
        $stmt = $this->db->prepare("
            INSERT INTO ACHATS (
                id_don_argent, id_besoin, quantite_achetee, 
                montant_unitaire, frais_achat_pourcentage, montant_total,
                est_simule, est_valide
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $id_don_argent,
            $id_besoin,
            $quantite,
            $montant_unitaire,
            $frais_pourcentage,
            $montant_total,
            $est_simule ? 1 : 0,
            0
        ]);
    }
    
    /**
     * Valider les achats simulés
     */
    public function validerSimulations() {
        $this->db->beginTransaction();
        
        try {
            // Récupérer tous les achats simulés
            $stmt = $this->db->query("
                SELECT * FROM ACHATS 
                WHERE est_simule = 1 AND est_valide = 0
            ");
            $achats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($achats as $achat) {
                // Créer un don correspondant à l'achat
                $stmt = $this->db->prepare("
                    INSERT INTO DONS (id_ville, demande, id_type_besoin, quantite, montant, date_don)
                    SELECT b.id_ville, b.demande, b.id_type_besoin, ?, ?, CURDATE()
                    FROM BESOINS b
                    WHERE b.id_besoin = ?
                ");
                $stmt->execute([
                    $achat['quantite_achetee'],
                    $achat['montant_total'],
                    $achat['id_besoin']
                ]);
                
                $id_don_cree = $this->db->lastInsertId();
                
                // Créer la distribution
                $stmt = $this->db->prepare("
                    INSERT INTO DISTRIBUTIONS (id_besoin, id_don, quantite_attribuee, date_distribution)
                    VALUES (?, ?, ?, CURDATE())
                ");
                $stmt->execute([
                    $achat['id_besoin'],
                    $id_don_cree,
                    $achat['quantite_achetee']
                ]);
                
                // Déduire le montant du don en argent
                $stmt = $this->db->prepare("
                    UPDATE DONS 
                    SET montant = montant - ?
                    WHERE id_don = ?
                ");
                $stmt->execute([
                    $achat['montant_total'],
                    $achat['id_don_argent']
                ]);
            }
            
            // Marquer tous les achats comme validés
            $this->db->exec("
                UPDATE ACHATS 
                SET est_simule = 0, est_valide = 1 
                WHERE est_simule = 1 AND est_valide = 0
            ");
            
            $this->db->commit();
            return true;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    /**
     * Supprimer toutes les simulations
     */
    public function supprimerSimulations() {
        $stmt = $this->db->prepare("DELETE FROM ACHATS WHERE est_simule = 1 AND est_valide = 0");
        return $stmt->execute();
    }
    
    /**
     * Récupérer tous les achats simulés
     */
    public function getSimulations() {
        $stmt = $this->db->query("
            SELECT 
                a.*,
                b.demande,
                b.quantite as quantite_besoin,
                v.nom_ville,
                r.nom_region,
                t.libelle as type_besoin
            FROM ACHATS a
            JOIN BESOINS b ON a.id_besoin = b.id_besoin
            JOIN VILLES v ON b.id_ville = v.id_ville
            JOIN REGION r ON v.id_region = r.id_region
            JOIN TYPE_BESOIN t ON b.id_type_besoin = t.id_type_besoin
            WHERE a.est_simule = 1 AND a.est_valide = 0
            ORDER BY a.date_achat DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupérer les achats validés
     */
    public function getAchatsValides() {
        $stmt = $this->db->query("
            SELECT 
                a.*,
                b.demande,
                v.nom_ville,
                r.nom_region,
                t.libelle as type_besoin
            FROM ACHATS a
            JOIN BESOINS b ON a.id_besoin = b.id_besoin
            JOIN VILLES v ON b.id_ville = v.id_ville
            JOIN REGION r ON v.id_region = r.id_region
            JOIN TYPE_BESOIN t ON b.id_type_besoin = t.id_type_besoin
            WHERE a.est_valide = 1
            ORDER BY a.date_achat DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}