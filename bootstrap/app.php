<?php
/**
 * ------------------------------------------------------
 * Bootstraping 
 * ------------------------------------------------------
 * @author     Egy Satria Hantoro <satriah0512@gmail.com>
 * @package    bootstarp/app.php
 * @version    v.1.1
 * @requires   Slim Framework, PSR-4 (Autoloading)
 */

session_start();

/*
 * ------------------------------------------------------
 *  Autoload composer vendor
 * ------------------------------------------------------
 */
require __DIR__ . '/../vendor/autoload.php';

/*
 * ------------------------------------------------------
 *  Load the framework Slim
 * ------------------------------------------------------
 */
$app = new \Slim\App([
	'settings'	=> [
		'displayErrorDetails'	=> true,
		'db'	=> [
			'driver'   	=> 'mysql',
			'host'	   	=> 'localhost',
			'database' 	=> 'db_slim',
			'username' 	=> 'root',
			'password' 	=> '',
			'charset'  	=> 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'	=> '',
		] 
	],
]);

$container = $app->getContainer();

/*
 * ------------------------------------------------------
 *  Load the Illuminate Database
 * ------------------------------------------------------
 */
$capsule   = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

/*
 * ------------------------------------------------------
 *  Set DB Container
 * ------------------------------------------------------
 */
$container['db']   = function( $container ) use ( $capsule ) {
	return $capsule;
};

/*
 * ------------------------------------------------------
 *  Settings Views Controller
 * ------------------------------------------------------
 */
$container['view'] = function( $container ) {
	$view = new \Slim\Views\Twig( __DIR__ . '/../resources/views', [
		'cache'	=> false,
	]);

	$view->addExtension(new \Slim\Views\TwigExtension(
		$container->router,
		$container->request->getUri()
	));
	return $view;
};
/*
 * ------------------------------------------------------
 *  Load the Validation
 * ------------------------------------------------------
 */
$container['validator'] = function( $container ) {
	return new \Egysatria\Validation\Validator;
};
/*
 * ------------------------------------------------------
 *  Load the Home Controller
 * ------------------------------------------------------
 */
$container['HomeController'] = function ($container) {
	return new \Egysatria\Controllers\HomeController( $container );
};

/*
 * ------------------------------------------------------
 *  Load the Auth Controller
 * ------------------------------------------------------
 */
$container['AuthController'] = function ($container) {
	return new \Egysatria\Controllers\Auth\AuthController( $container );
};

$app->add( new \Egysatria\Middleware\ValidationErrorsMiddleware( $container ) );

/*
 * ------------------------------------------------------
 *  Load the router
 * ------------------------------------------------------
 */
require __DIR__ . '/../app/routes.php';