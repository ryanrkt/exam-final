<?php

use flight\Engine;
use flight\net\Router;
use app\controllers\DashboardController;
use app\controllers\DonController;
use app\controllers\BesoinsController;
use app\middlewares\SecurityHeadersMiddleware;
use app\models\Ville;
use app\models\Besoin;


/** 
 * @var Router $router 
 * @var Engine $app
 */

// Autoload des controllers
spl_autoload_register(function ($class) {
    // Support namespaced classes like "app\controllers\MyController"
    // Convert namespace separators to directory separators
    $path = str_replace('\\', '/', $class);

    // If class starts with 'app/', remove that prefix because we're already in app/
    if (strpos($path, 'app/') === 0) {
        $path = substr($path, 4);
    }

    $file = __DIR__ . '/../' . $path . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }

    // Fallback: try previous behaviour for non-namespaced class names
    $fallback = __DIR__ . '/../controllers/' . $class . '.php';
    if (file_exists($fallback)) {
        require_once $fallback;
        return true;
    }

    return false;
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

    // Routes pour dons
    $router->get('/dons', function () use ($app) {
        $controller = new DonController();
        $controller->index();
    });

    $router->post('/dons/create', function () use ($app) {
        $controller = new DonController();
        $controller->create();
    });
    
    $router->get('/dons/edit/@id', function ($id) use ($app) {
        $controller = new DonController();
        $controller->edit($id);
    });
    
    $router->post('/dons/update/@id', function ($id) use ($app) {
        $controller = new DonController();
        $controller->update($id);
    });
    
    $router->get('/dons/delete/@id', function ($id) use ($app) {
        $controller = new DonController();
        $controller->delete($id);
    });

    // Routes pour besoins
    $router->get('/besoins', function () use ($app) {
        $controller = new BesoinsController();
        $controller->index();
    });

    $router->post('/besoins/create', function () use ($app) {
        $controller = new BesoinsController();
        $controller->create();
    });
    
    $router->get('/besoins/edit/@id', function ($id) use ($app) {
        $controller = new BesoinsController();
        $controller->edit($id);
    });
    
    $router->post('/besoins/update/@id', function ($id) use ($app) {
        $controller = new BesoinsController();
        $controller->update($id);
    });
    
    $router->get('/besoins/delete/@id', function ($id) use ($app) {
        $controller = new BesoinsController();
        $controller->delete($id);
    });

    $router->get('/simulation', function () use ($app) {
        $app->render('simulation/index');
    });

}, [SecurityHeadersMiddleware::class]);