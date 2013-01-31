<?php

use Http\Request;
use Http\Response;
use Http\JsonResponse;
use Exception\HttpException;
use Exception\NotFoundException;
use Dal\Connection;

require __DIR__.'/../vendor/autoload.php';

// Config
$debug = true;

$dsn = 'mysql:host=localhost;dbname=uframework' ;
$user = 'uframework' ;
$password = 'uframework123';

$app = new \App(new View\TemplateEngine(
    __DIR__ . '/templates/'
), $debug);

//Connection
$con = new Connection($dsn, $user, $password);

/**
 * Index
 */
$app->get('/', function (Request $request) use ($app) {
    return $app->render('index.php');
});

/**
 * Locations
 */
$app->get('/locations', function (Request $request) use ($app, $con) {
    $loc = new Model\Locations($con);
    $cities = $loc->findAll();
  
    switch($request->guessBestFormat()) {
        case 'json' :
            return new JsonResponse($cities);

        default :
    }
      
    return $app->render('locations.php', array('cities' => $cities));
});

/**
 * Location
 */
$app->get('/locations/(\d+)', function (Request $request, $id) use ($app, $con) {
    $loc = new Model\Locations($con);
    $city = $loc->findOneById($id);
 
    if(false === isset($city)) {
        throw new NotFoundException('City not found !');
    }

    switch($request -> guessBestFormat()) { 
        case 'json' :
            return new JsonResponse(["id" => $city->getId(), "location" => $city->getName()]);
        
        default :
    }

    return $app->render('location.php', ["id" => $city->getId(), "location" => $city->getName()]);
});

/**
 * Post
 */
$app->post('/locations', function (Request $request) use ($app, $con) {
    $parameter = $request->getParameter("name", "POST");
    $loc = new Model\Locations($con);

    if(true === empty($parameter)) {
        throw new HttpException(400, 'Name cannot be empty !');
    }

    $id = $loc->create($parameter);

    switch($request -> guessBestFormat()) { 
        case 'json' :
            return new JsonResponse($id, 201);
        
        default :
    }
    
    return $app->redirect('/locations');
});

/**
 * Put
 */
$app->put('/locations/(\d+)', function (Request $request, $id) use ($app, $con) { 
    $name = $request->getParameter("name", "POST");  
    $loc = new Model\Locations($con);
    $city = $loc->findOneById($id);

    if(false === isset($city)) {
        throw new NotFoundException('City not found !');
    }

    if(true === empty($name)) {
        throw new HttpException(400, 'Name cannot be empty !');
    }

    $loc->update($id, $name);
    $city = $loc->findOneById($id);

    switch($request -> guessBestFormat()) {
        case 'json' :
            return new JsonResponse(["id" => $city["id"], "location" => $city["name"]]);

        default :
    }
            
    return $app->redirect('/locations/'.$id);
});

/**
 * Delete
 */
$app->delete('/locations/(\d+)', function (Request $request, $id) use ($app, $con) {
    $loc = new Model\Locations($con);
    $city = $loc->findOneById($id);
 
    if(false === isset($city)) {
        throw new NotFoundException('City not found !');
    }

    $loc->delete($id);
    
    switch($request -> guessBestFormat()) {
        case 'json' :
            return new JsonResponse($id, 204);

        default :
    }

    return $app->redirect('/locations');
});


return $app;
