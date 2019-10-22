<?php
$loader = require 'vendor/autoload.php';


$app = new \Slim\Slim(array(
    'templates.path' => 'templates'
));


//Consults Routes
$app->get('/consults/', function() use ($app){
	(new \controllers\Consults($app))->list();
});

$app->get('/consults/:id', function($id) use ($app){
	(new \controllers\Consults($app))->get($id);
});

$app->post('/consults/', function() use ($app){
	(new \controllers\Consults($app))->new();
});

$app->put('/consults/:id', function($id) use ($app){
	(new \controllers\Consults($app))->edit($id);
});

$app->delete('/consults/:id', function($id) use ($app){
	(new \controllers\Consults($app))->delete($id);
});



//Vehicles Routes
$app->get('/vehicles/', function() use ($app){
	(new \controllers\Vehicles($app))->list();
});

$app->get('/vehicles/:id', function($id) use ($app){
	(new \controllers\Vehicles($app))->get($id);
});

$app->get('/vehicles-top/', function() use ($app){
	(new \controllers\Vehicles($app))->listTop();
});

$app->post('/vehicles/', function() use ($app){
	(new \controllers\Vehicles($app))->new();
});

$app->put('/vehicles/:id', function($id) use ($app){
	(new \controllers\Vehicles($app))->edit($id);
});

$app->delete('/vehicles/:id', function($id) use ($app){
	(new \controllers\Vehicles($app))->delete($id);
});

$app->put('/vehicles/:id', function($id) use ($app){
	(new \controllers\Vehicles($app))->incrementConsult($id);
});

$app->run();