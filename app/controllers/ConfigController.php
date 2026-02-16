<?php
namespace app\controllers;

use app\models\Ville;
use app\models\TypeBesoin;
use Flight;

class ConfigController
{
    /**
     * Affiche la page de configuration
     */
    public function index()
    {
        $villeModel = new Ville(Flight::db());
        $typeBesoinModel = new TypeBesoin(Flight::db());

        Flight::render('config/index', [
            'villes' => $villeModel->getAll(),
            'types_besoin' => $typeBesoinModel->getAll()
        ]);
    }
}
