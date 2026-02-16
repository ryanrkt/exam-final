<?php

use app\controllers\AuthController;
use app\controllers\DashboardController;
use app\controllers\DonController;
use app\controllers\BesoinsController;
use app\controllers\SimulationController;
use app\controllers\ConfigController;
use app\middlewares\SecurityHeadersMiddleware;
use app\middlewares\AdminMiddleware;
use app\middlewares\ClientMiddleware;
use flight\Engine;
use flight\net\Router;
use app\controllers\AdminController;

/** 
 * @var Router $router 
 * @var Engine $app
 */


$router->group('', function(Router $router) use ($app) {

	$router->get('/test', function() use ($app) {
		$db = Flight::db();
		if ($db) {
			echo "Database connection successful!";
		} else {
			echo "Database connection failed.";
		}
	});

	$router->get('/', function() use ($app) {
		$nonce = $app->get('csp_nonce');
		$app->render('home', ['nonce' => $nonce]);
	});

	// Routes d'authentification
	$router->post('/login', function() use ($app) {
		$controller = new AuthController($app);
		$controller->login();
	});

	$router->post('/register', function() use ($app) {
		$controller = new AuthController($app);
		$controller->register();
	});

	$router->get('/logout', function() use ($app) {
		$controller = new AuthController($app);
		$controller->logout();
	});

	// Routes BNGRC Dashboard
	$router->get('/dashboard', function() use ($app) {
		$controller = new DashboardController();
		$controller->index();
	});

	// Routes Dons
	$router->get('/dons', function() use ($app) {
		$controller = new DonController();
		$controller->index();
	});

	$router->get('/dons/create', function() use ($app) {
		$controller = new DonController();
		$controller->create();
	});

	$router->post('/dons', function() use ($app) {
		$controller = new DonController();
		$controller->create();
	});

	// Routes Besoins
	$router->get('/besoins', function() use ($app) {
		$controller = new BesoinsController();
		$controller->index();
	});

	$router->get('/besoins/create', function() use ($app) {
		$controller = new BesoinsController();
		$controller->create();
	});

	$router->post('/besoins', function() use ($app) {
		$controller = new BesoinsController();
		$controller->create();
	});

	// Routes Simulation
	$router->get('/simulation', function() use ($app) {
		$controller = new SimulationController();
		$controller->index();
	});

	$router->post('/simulation/run', function() use ($app) {
		$controller = new SimulationController();
		$controller->run();
	});

	// Routes Configuration
	$router->get('/config', function() use ($app) {
		$controller = new ConfigController();
		$controller->index();
	});

}, [SecurityHeadersMiddleware::class]);

// Routes Admin 
$router->group('/admin', function(Router $router) use ($app) {
	
	$router->get('/', function() use ($app) {
		$controller = new AdminController();
		$controller->Dashboard();
	});

	$router->get('/utilisateurs', function() use ($app) {
		$nonce = $app->get('csp_nonce');
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		$user = $_SESSION['user'] ?? [];
		$app->render('admin/utilisateurs', ['nonce' => $nonce, 'user' => $user]);
	});

}, [SecurityHeadersMiddleware::class, AdminMiddleware::class]);

// Routes Client 
$router->group('/client', function(Router $router) use ($app) {
	
	$router->get('/', function() use ($app) {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		$nonce = $app->get('csp_nonce');
		$user = $_SESSION['user'] ?? [];
		
		// Statistiques 
		$stats = [
			'mes_objets' => 0,
			'mes_echanges' => 0,
			'en_attente' => 0
		];
		
		$app->render('client/home', [
			'nonce' => $nonce,
			'user' => $user,
			'stats' => $stats,
			'objets_recents' => []
		]);
	});

	$router->get('/objets', function() use ($app) {
		$nonce = $app->get('csp_nonce');
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		$user = $_SESSION['user'] ?? [];
		$app->render('client/objets', ['nonce' => $nonce, 'user' => $user]);
	});

	$router->get('/profil', function() use ($app) {
		$nonce = $app->get('csp_nonce');
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		$user = $_SESSION['user'] ?? [];
		$app->render('client/profil', ['nonce' => $nonce, 'user' => $user]);
	});

}, [SecurityHeadersMiddleware::class, ClientMiddleware::class]);
