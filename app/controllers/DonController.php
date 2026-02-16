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

        Flight::render('dons/index', [
            'dons' => $dons
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id_ville' => $_POST['id_ville'],
                'id_type_besoin' => $_POST['id_type_besoin'],
                'quantite' => $_POST['quantite'],
                'montant' => $_POST['montant'],
                'date_don' => $_POST['date_don']
            ];

            $model = new Don(Flight::db());
            $model->create(
                $data['id_ville'],
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
}