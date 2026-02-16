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
                'id_ville' => $_POST['id_ville'] ?? null,
                'id_type_besoin' => $_POST['id_type_besoin'] ?? null,
                'quantite' => $_POST['quantite'] ?? null,
                'montant' => $_POST['montant'] ?? null,
                'date_don' => $_POST['date_don'] ?? date('Y-m-d')
            ];

            // Validation simple
            if ($data['id_ville'] && $data['id_type_besoin'] && $data['quantite'] && $data['montant']) {
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
        }

        // Afficher le formulaire avec données pré-remplies
        $villeModel = new Ville(Flight::db());
        $typeBesoinModel = new TypeBesoin(Flight::db());

        Flight::render('dons/index', [
            'dons' => [],
            'villes' => $villeModel->getAll(),
            'types_besoin' => $typeBesoinModel->getAll()
        ]);
    }
}