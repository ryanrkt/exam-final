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

	
	// Routes BNGRC Dashboard
	$router->get('/dashboard', function() use ($app) {
		$app->render('dashboard/index');
	});

	$router->get('/dons', function() use ($app) {
		$app->render('dons/index');
	});

	$router->get('/besoins', function() use ($app) {
		$app->render('besoins/index');
	});

	$router->get('/simulation', function() use ($app) {
		$app->render('simulation/index');
	});

}, [SecurityHeadersMiddleware::class]);


