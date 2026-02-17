<?php
namespace app\models;
use PDO;

class Config {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getValeur($cle) {
        $stmt = $this->db->prepare("SELECT valeur FROM CONFIG WHERE cle = ?");
        $stmt->execute([$cle]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['valeur'] : null;
    }
    
    public function setValeur($cle, $valeur) {
        $stmt = $this->db->prepare("
            INSERT INTO CONFIG (cle, valeur) 
            VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE valeur = ?
        ");
        return $stmt->execute([$cle, $valeur, $valeur]);
    }
    
    public function getFraisAchatPourcentage() {
        return (float)$this->getValeur('frais_achat_pourcentage');
    }
}