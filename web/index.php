<?php
date_default_timezone_set('America/Bogota');
require('../vendor/autoload.php');
$app = new Silex\Application();
$app['debug'] = true;
// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));
// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));
// Our web handlers
$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});
$app->get('/fecha', function() use($app){
  $app['monolog']->addDebug('logging output.');
  return date('Y-m-d H:i:s');
});
$app->get('/guardar/{temperatura_ambiente}/{humedad_suelo}/{humedad_ambiente}/{luz}', 
	function($temperatura_ambiente, $humedad_suelo, $humedad_ambiente,$luz) use($app){
	$dbconexion=pg_connect( "host=ec2-23-21-192-179.compute-1.amazonaws.com port=5432 dbname=d7668c6higkn8l user=dvtjsetxbqhets password=e805ee92c1736a560cb20ce9bd4f3f967fd85b6b4baa4c6ee2934bfece6430b0");
	$registro=array (
		"FECHA"=>date('Y-m-d H:i:s'),
		"TEMPERATURA"=>$temperatura_ambiente),
		"HUMEDADSUELO"=>$humedad_suelo),
		"HUMEDADAIRE"=>$humedad_ambiente),
		"ILUMINACION"=>$luz);
	$resultado=pg_insert ($dbconexion,'PARAMETROS',$registro);
	return date('Y-m-d H:i:s');
	});
$app->run();  
