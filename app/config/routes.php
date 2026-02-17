<?php

use flight\Engine;
use flight\net\Router;
use app\controllers\DashboardController;
use app\controllers\DonController;
use app\controllers\BesoinsController;
use app\controllers\AchatsController;
use app\controllers\SimulationController;
use app\middlewares\SecurityHeadersMiddleware;

/** 
 * @var Router $router 
 * @var Engine $app
 */

// ========================================
// AUTOLOAD DES CLASSES
// ========================================
spl_autoload_register(function ($class) {
    // Support des classes namespacées comme "app\controllers\MyController"
    $path = str_replace('\\', '/', $class);

    // Si la classe commence par 'app/', on retire ce préfixe
    if (strpos($path, 'app/') === 0) {
        $path = substr($path, 4);
    }

    // Essayer de charger depuis app/
    $file = __DIR__ . '/../' . $path . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }

    // Fallback : essayer depuis app/controllers/
    $fallback = __DIR__ . '/../controllers/' . $class . '.php';
    if (file_exists($fallback)) {
        require_once $fallback;
        return true;
    }

    return false;
});

// ========================================
// GROUPE DE ROUTES AVEC MIDDLEWARE
// ========================================
$router->group('', function (Router $router) use ($app) {

    // ========================================
    // TEST & DEBUG
    // ========================================
    $router->get('/test', function () use ($app) {
        $db = Flight::db();
        if ($db) {
            echo "✅ Database connection successful!<br>";
            try {
                $stmt = $db->query("SELECT COUNT(*) as count FROM REGION");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "Nombre de régions : " . $result['count'];
            } catch (Exception $e) {
                echo "❌ Erreur : " . $e->getMessage();
            }
        } else {
            echo "❌ Database connection failed.";
        }
    });

    // ========================================
    // DASHBOARD
    // ========================================
    $router->get('/', function () use ($app) {
        $controller = new DashboardController();
        $controller->index();
    });

    $router->get('/dashboard', function () use ($app) {
        $controller = new DashboardController();
        $controller->index();
    });

    // ========================================
    // GESTION DES DONS
    // ========================================
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

    // ========================================
    // GESTION DES BESOINS
    // ========================================
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

    // ========================================
    // ACHATS AVEC DONS EN ARGENT
    // ========================================
    $router->get('/achats/besoins-restants', function () use ($app) {
        $controller = new AchatsController();
        $controller->besoinsRestants();
    });

    $router->post('/achats/simuler', function () use ($app) {
        $controller = new AchatsController();
        $controller->simuler();
    });

    $router->get('/achats/simulation', function () use ($app) {
        $controller = new AchatsController();
        $controller->pageSimulation();
    });

    $router->post('/achats/valider', function () use ($app) {
        $controller = new AchatsController();
        $controller->valider();
    });

    $router->post('/achats/annuler', function () use ($app) {
        $controller = new AchatsController();
        $controller->annuler();
    });

    $router->get('/achats/recapitulatif', function () use ($app) {
        $controller = new AchatsController();
        $controller->recapitulatif();
    });

    // ========================================
    // SIMULATION DISPATCH (ancien système)
    // ========================================
    $router->get('/simulation', function () use ($app) {
        $controller = new SimulationController();
        $controller->index();
    });
    
    $router->post('/api/simulation/execute', function () use ($app) {
        $controller = new SimulationController();
        $controller->executeSimulation();
    });
    
    $router->post('/api/simulation/valider', function () use ($app) {
        $controller = new SimulationController();
        $controller->validerSimulation();
    });
    
    $router->get('/api/simulation/historique', function () use ($app) {
        $controller = new SimulationController();
        $controller->getHistorique();
    });
    
    $router->post('/api/simulation/reset', function () use ($app) {
        $controller = new SimulationController();
        $controller->resetDistributions();
    });

    // ========================================
    // CONFIGURATION (optionnel)
    // ========================================
    $router->get('/config/frais', function () use ($app) {
        $db = Flight::db();
        $configModel = new \app\models\Config($db);
        $frais = $configModel->getFraisAchatPourcentage();
        
        Flight::render('config/frais', ['frais_pourcentage' => $frais]);
    });

    $router->post('/config/frais/update', function () use ($app) {
        $db = Flight::db();
        $configModel = new \app\models\Config($db);
        
        $nouveau_frais = $_POST['frais_pourcentage'] ?? 10;
        $configModel->setValeur('frais_achat_pourcentage', $nouveau_frais);
        
        Flight::redirect('/config/frais');
    });

    // ========================================
    // RECHERCHE (placeholder)
    // ========================================
    $router->post('/search', function () use ($app) {
        $query = $_POST['query'] ?? '';
        // TODO: Implémenter la recherche
        Flight::redirect('/dashboard');
    });

    // ========================================
    // DÉCONNEXION (placeholder)
    // ========================================
    $router->get('/logout', function () use ($app) {
        session_destroy();
        Flight::redirect('/');
    });

}, [SecurityHeadersMiddleware::class]);

// ========================================
// GESTION DES ERREURS
// ========================================
Flight::map('notFound', function() {
    Flight::render('errors/404', [], 'content');
    Flight::render('layouts/main');
});

Flight::map('error', function(Exception $e) {
    echo '<h1>Erreur serveur</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
});