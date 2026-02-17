<?php
namespace app\controllers;

use app\models\Don;
use app\models\Ville;
use app\models\TypeBesoin;
use Flight;

class DonController {
    
    public function index() {
        $db = Flight::db();
        $donModel = new Don($db);
        $dons = $donModel->getAll();
        
        $typeBesoinModel = new TypeBesoin($db);
        
        Flight::render('dons/index', [
            'dons' => $dons,
            'types_besoin' => $typeBesoinModel->getAll()
        ]);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = Flight::db();
            $donModel = new Don($db);
            
            // La ville sera attribuée automatiquement lors du dispatch
            $id_ville = null;
            $demande = $_POST['demande'] ?? '';
            $id_type_besoin = $_POST['id_type_besoin'];
            $quantite = $_POST['quantite'];
            $montant = $_POST['montant'];
            $date_don = $_POST['date_don'];
            
            $donModel->create($id_ville, $demande, $id_type_besoin, $quantite, $montant, $date_don);
            
            Flight::redirect('/dons');
        }
    }
    
    public function edit($id) {
        $db = Flight::db();
        $donModel = new Don($db);
        $don = $donModel->getById($id);
        
        if (!$don) {
            Flight::redirect('/dons');
            return;
        }
        
        $typeBesoinModel = new TypeBesoin($db);
        
        Flight::render('dons/edit', [
            'don' => $don,
            'types_besoin' => $typeBesoinModel->getAll()
        ]);
    }
    
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = Flight::db();
            $donModel = new Don($db);
            
            // Conserver la ville existante (attribuée par le dispatch)
            $don = $donModel->getById($id);
            $id_ville = $don['id_ville'] ?? null;
            $demande = $_POST['demande'] ?? '';
            $id_type_besoin = $_POST['id_type_besoin'];
            $quantite = $_POST['quantite'];
            $montant = $_POST['montant'];
            $date_don = $_POST['date_don'];
            
            $donModel->update($id, $id_ville, $demande, $id_type_besoin, $quantite, $montant, $date_don);
            
            Flight::redirect('/dons');
        }
    }
    
    public function delete($id) {
        $db = Flight::db();
        $donModel = new Don($db);
        $donModel->delete($id);
        
        Flight::redirect('/dons');
    }
}