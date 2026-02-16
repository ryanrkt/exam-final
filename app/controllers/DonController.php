<?php 
namespace app\controllers;

use app\models\Don;
use app\models\Ville;
use app\models\TypeBesoin;
use Flight;

class DonController
{
    public function index()
    {
        $model = new Don(Flight::db());
        $dons = $model->getAll();

        $typeBesoinModel = new TypeBesoin(Flight::db());

        Flight::render('dons/index', [
            'dons' => $dons,
            'types_besoin' => $typeBesoinModel->getAll()
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id_type_besoin' => $_POST['id_type_besoin'],
                'quantite' => $_POST['quantite'],
                'montant' => $_POST['montant'],
                'date_don' => $_POST['date_don']
            ];

            $model = new Don(Flight::db());
            $model->create(
                null,
                $data['id_type_besoin'],
                $data['quantite'],
                $data['montant'],
                $data['date_don']
            );

            Flight::redirect('/dons');
        }

        $villeModel = new Ville(Flight::db());
        $typeBesoinModel = new TypeBesoin(Flight::db());

        Flight::render('dons/create', [
            'villes' => $villeModel->getAll(),
            'types_besoin' => $typeBesoinModel->getAll()
        ]);
    }
    
    public function edit($id)
    {
        $model = new Don(Flight::db());
        $don = $model->getById($id);
        
        if (!$don) {
            Flight::redirect('/dons');
            return;
        }
        
        $typeBesoinModel = new TypeBesoin(Flight::db());
        
        Flight::render('dons/edit', [
            'don' => $don,
            'types_besoin' => $typeBesoinModel->getAll()
        ]);
    }
    
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id_type_besoin' => $_POST['id_type_besoin'],
                'quantite' => $_POST['quantite'],
                'montant' => $_POST['montant'],
                'date_don' => $_POST['date_don']
            ];

            $model = new Don(Flight::db());
            $model->update(
                $id,
                null,
                $data['id_type_besoin'],
                $data['quantite'],
                $data['montant'],
                $data['date_don']
            );

            Flight::redirect('/dons');
        }
    }
    
    public function delete($id)
    {
        $model = new Don(Flight::db());
        $model->delete($id);
        Flight::redirect('/dons');
    }
}