<?php

use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

// Autoload des controllers
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../controllers/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

$router->group('', function (Router $router) use ($app) {

    // Test connexion DB
    $router->get('/test', function () use ($app) {
        $db = Flight::db();
        if ($db) {
            echo "Database connection successful!";
        } else {
            echo "Database connection failed.";
        }
    });

    // Page d'accueil = Dashboard
    $router->get('/', function () use ($app) {
        $controller = new DashboardController();
        $controller->index();
    });

    // Dashboard
    $router->get('/dashboard', function () use ($app) {
        $controller = new DashboardController();
        $controller->index();
    });

    // Pages vides pour les liens du menu (temporaire)
    $router->get('/dons', function () use ($app) {
        $app->render('dons/index');
    });

    $router->get('/besoins', function () use ($app) {
        $app->render('besoins/index');
    });

    $router->get('/simulation', function () use ($app) {
        $app->render('simulation/index');
    });

}, [SecurityHeadersMiddleware::class]);