<?php

use app\controllers\AuthController;
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

// Routes publiques (page d'accueil, connexion, inscription)
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