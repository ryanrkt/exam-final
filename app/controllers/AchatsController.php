<?php
namespace app\controllers;

use app\models\Achat;
use app\models\Besoin;
use app\models\Config;
use Flight;
use PDO;

class AchatsController {
    
    /**
     * Page des besoins restants (pour faire les achats)
     */
    public function besoinsRestants() {
        $db = Flight::db();
        
        // Filtre par ville
        $filter_ville = isset($_GET['ville']) && $_GET['ville'] !== '' ? (int)$_GET['ville'] : null;
        
        // Récupérer les besoins non satisfaits (Nature + Matériaux uniquement, pas Argent)
        $sql = "
            SELECT 
                b.*,
                v.nom_ville,
                r.nom_region,
                t.libelle as type_besoin,
                (b.quantite * b.prix_unitaire) as montant_total,
                COALESCE(
                    (SELECT SUM(dist.quantite_attribuee) FROM DISTRIBUTIONS dist WHERE dist.id_besoin = b.id_besoin), 0
                ) + COALESCE(
                    (SELECT SUM(a.quantite_achetee) FROM ACHATS a WHERE a.id_besoin = b.id_besoin AND a.est_valide = 1), 0
                ) as quantite_satisfaite
            FROM BESOINS b
            JOIN VILLES v ON b.id_ville = v.id_ville
            JOIN REGION r ON v.id_region = r.id_region
            JOIN TYPE_BESOIN t ON b.id_type_besoin = t.id_type_besoin
            WHERE t.libelle != 'Argent'
        ";
        $params = [];
        
        if ($filter_ville !== null) {
            $sql .= " AND b.id_ville = ?";
            $params[] = $filter_ville;
        }
        
        $sql .= "
            HAVING (b.quantite - quantite_satisfaite) > 0
            ORDER BY b.date_creation ASC
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $besoins_restants_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculer quantite_restante
        $besoins_restants = [];
        foreach ($besoins_restants_raw as $b) {
            $b['quantite_restante'] = $b['quantite'] - $b['quantite_satisfaite'];
            $besoins_restants[] = $b;
        }
        
        // Récupérer les dons en argent disponibles
        $stmt = $db->query("
            SELECT 
                d.*,
                v.nom_ville,
                r.nom_region,
                (d.montant - COALESCE(
                    (SELECT SUM(a.montant_total) FROM ACHATS a WHERE a.id_don_argent = d.id_don AND (a.est_valide = 1 OR a.est_simule = 1)), 0
                )) as montant_disponible
            FROM DONS d
            JOIN VILLES v ON d.id_ville = v.id_ville
            JOIN REGION r ON v.id_region = r.id_region
            JOIN TYPE_BESOIN t ON d.id_type_besoin = t.id_type_besoin
            WHERE t.libelle = 'Argent'
            HAVING montant_disponible > 0
            ORDER BY d.date_don ASC
        ");
        $dons_argent = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Récupérer le pourcentage de frais
        $configModel = new Config($db);
        $frais_pourcentage = $configModel->getFraisAchatPourcentage();
        
        // Récupérer toutes les villes pour le filtre
        $stmt = $db->query("SELECT v.*, r.nom_region FROM VILLES v JOIN REGION r ON v.id_region = r.id_region ORDER BY v.nom_ville");
        $villes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        Flight::render('achats/besoins_restants', [
            'besoins_restants' => $besoins_restants,
            'dons_argent' => $dons_argent,
            'frais_pourcentage' => $frais_pourcentage,
            'villes' => $villes,
            'filter_ville' => $filter_ville
        ]);
    }
    
    /**
     * Simuler un achat (POST JSON)
     */
    public function simuler() {
        $db = Flight::db();
        
        try {
            $id_besoin = $_POST['id_besoin'] ?? null;
            $id_don_argent = $_POST['id_don_argent'] ?? null;
            $quantite = (int)($_POST['quantite'] ?? 0);
            $prix_unitaire = (float)($_POST['prix_unitaire'] ?? 0);
            
            if (!$id_besoin || !$id_don_argent || $quantite <= 0) {
                Flight::json(['success' => false, 'message' => 'Données invalides']);
                return;
            }
            
            $configModel = new Config($db);
            $frais_pourcentage = $configModel->getFraisAchatPourcentage();
            
            $achatModel = new Achat($db);
            
            // Vérifier si un achat existe déjà pour ce besoin (simulé ou validé)
            if ($achatModel->achatExiste($id_besoin)) {
                Flight::json(['success' => false, 'message' => 'Un achat existe déjà pour ce besoin (simulé ou validé).']);
                return;
            }
            
            // Vérifier que le don en argent a assez de montant
            $stmt = $db->prepare("
                SELECT d.montant - COALESCE(
                    (SELECT SUM(a.montant_total) FROM ACHATS a WHERE a.id_don_argent = d.id_don AND a.est_valide = 1), 0
                ) - COALESCE(
                    (SELECT SUM(a.montant_total) FROM ACHATS a WHERE a.id_don_argent = d.id_don AND a.est_simule = 1 AND a.est_valide = 0), 0
                ) as montant_disponible
                FROM DONS d WHERE d.id_don = ?
            ");
            $stmt->execute([$id_don_argent]);
            $don = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$don) {
                Flight::json(['success' => false, 'message' => 'Don introuvable']);
                return;
            }
            
            $montant_achat = $quantite * $prix_unitaire * (1 + $frais_pourcentage / 100);
            
            if ($montant_achat > $don['montant_disponible']) {
                Flight::json(['success' => false, 'message' => 'Montant insuffisant dans le don sélectionné. Disponible: ' . number_format($don['montant_disponible'], 0, ',', ' ') . ' Ar, Requis: ' . number_format($montant_achat, 0, ',', ' ') . ' Ar']);
                return;
            }
            
            // Créer l'achat en simulation
            $achatModel->creer($id_don_argent, $id_besoin, $quantite, $prix_unitaire, $frais_pourcentage, true);
            
            Flight::json([
                'success' => true, 
                'message' => 'Achat simulé avec succès ! Montant total: ' . number_format($montant_achat, 0, ',', ' ') . ' Ar (dont ' . $frais_pourcentage . '% de frais)'
            ]);
            
        } catch (\Exception $e) {
            Flight::json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Page de simulation des achats
     */
    public function pageSimulation() {
        $db = Flight::db();
        $achatModel = new Achat($db);
        
        $simulations = $achatModel->getSimulations();
        
        $total_simulations = 0;
        foreach ($simulations as $sim) {
            $total_simulations += $sim['montant_total'];
        }
        
        Flight::render('achats/simulation', [
            'simulations' => $simulations,
            'total_simulations' => $total_simulations
        ]);
    }
    
    /**
     * Valider les achats simulés (POST JSON)
     */
    public function valider() {
        $db = Flight::db();
        
        try {
            $achatModel = new Achat($db);
            $achatModel->validerSimulations();
            
            Flight::json([
                'success' => true,
                'message' => 'Tous les achats ont été validés et les dons ont été distribués !'
            ]);
        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la validation: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Annuler les simulations (POST JSON)
     */
    public function annuler() {
        $db = Flight::db();
        
        try {
            $achatModel = new Achat($db);
            $achatModel->supprimerSimulations();
            
            Flight::json([
                'success' => true,
                'message' => 'Toutes les simulations ont été annulées.'
            ]);
        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Page de récapitulation (GET = HTML, AJAX = JSON)
     */
    public function recapitulatif() {
        $db = Flight::db();
        
        // Besoins totaux
        $stmt = $db->query("
            SELECT COALESCE(SUM(b.quantite * b.prix_unitaire), 0) as total
            FROM BESOINS b
        ");
        $total_besoins = (float)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Besoins satisfaits via distributions (utiliser le montant des dons distribués)
        $stmt = $db->query("
            SELECT COALESCE(SUM(d.montant), 0) as total
            FROM DISTRIBUTIONS dist
            JOIN DONS d ON dist.id_don = d.id_don
        ");
        $total_satisfaits_dist = (float)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Besoins satisfaits via achats validés
        $stmt = $db->query("
            SELECT COALESCE(SUM(a.quantite_achetee * a.montant_unitaire), 0) as total
            FROM ACHATS a
            WHERE a.est_valide = 1
        ");
        $total_satisfaits_achats = (float)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $total_satisfaits = $total_satisfaits_dist + $total_satisfaits_achats;
        $total_restants = max(0, $total_besoins - $total_satisfaits);
        $pourcentage_satisfait = $total_besoins > 0 ? ($total_satisfaits / $total_besoins * 100) : 0;
        
        // Ajax => JSON
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            Flight::json([
                'total_besoins' => $total_besoins,
                'total_satisfaits' => $total_satisfaits,
                'total_restants' => $total_restants,
                'pourcentage_satisfait' => $pourcentage_satisfait
            ]);
            return;
        }
        
        Flight::render('achats/recapitulatif', [
            'total_besoins' => $total_besoins,
            'total_satisfaits' => $total_satisfaits,
            'total_restants' => $total_restants,
            'pourcentage_satisfait' => $pourcentage_satisfait
        ]);
    }
}