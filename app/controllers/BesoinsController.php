<?php 
namespace app\controllers;

use app\models\Besoin;
use app\models\Ville;
use app\models\TypeBesoin;
use Flight;

class BesoinsController
{
    public function index()
    {
        $model = new Besoin(Flight::db());
        $besoins = $model->getAll();

        $villeModel = new Ville(Flight::db());
        $typeBesoinModel = new TypeBesoin(Flight::db());

        Flight::render('besoins/index', [
            'besoins' => $besoins,
            'villes' => $villeModel->getAll(),
            'types_besoin' => $typeBesoinModel->getAll()
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id_ville' => $_POST['id_ville'],
                'id_type_besoin' => $_POST['id_type_besoin'],
                'quantite' => $_POST['quantite'],
                'prix_unitaire' => $_POST['prix_unitaire']
            ];

            $model = new Besoin(Flight::db());
            $model->create(
                $data['id_ville'],
                $data['id_type_besoin'],
                $data['quantite'],
                $data['prix_unitaire']
            );

            Flight::redirect('/besoins');
        }

        $villeModel = new Ville(Flight::db());
        $typeBesoinModel = new TypeBesoin(Flight::db());

        Flight::render('besoins/create', [
            'villes' => $villeModel->getAll(),
            'types_besoin' => $typeBesoinModel->getAll()
        ]);
    }
}