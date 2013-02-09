<?php

use Model\LocationFinder;
use Model\LocationDataMapper;
use Model\Location;
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
    $loc = new LocationFinder($con);
    $cities = $loc->findAll();
  
    switch($request->guessBestFormat()) {
        case 'json' :
            return new JsonResponse($cities);

        default :
    }
      
    return $app->render('locations.php', ['cities' => $cities]);
});

/**
 * Location
 */
$app->get('/locations/(\d+)', function (Request $request, $id) use ($app, $con) {
    $loc = new LocationFinder($con);
    $city = $loc->findOneById($id);
 
    if(false === isset($city)) {
        throw new NotFoundException('City not found !');
    }

    switch($request -> guessBestFormat()) { 
        case 'json' :
            return new JsonResponse($city);
        
        default :
    }

    return $app->render('location.php', ['id' => $city->getId(), 'location' => $city->getName(), 'createdAt' => $city->getCreatedAt(), 'comments' => $city->getComments()]);
});

/**
 * Comments
 */
$app->get('/locations/(\d+)/comments', function (Request $request, $id) use ($app, $con) {
    $loc = new LocationFinder($con);
    $city = $loc->findOneById($id);

    if(false === isset($city)) {
        throw new NotFoundException('City not found !');
    }

    return new JsonResponse(["comments" => $city->getComments()]);
});

/**
 * Post
 */
$app->post('/locations', function (Request $request) use ($app, $con) {
    $parameter = $request->getParameter("name", "POST");
    
    $mapper = new LocationDataMapper($con);
    $date = new \DateTime(null);
    $location = new Location($parameter, $date);
    
    if(true === empty($parameter)) {
        throw new HttpException(400, 'Name cannot be empty !');
    }
       
    $mapper->persist($location);
    
    switch($request -> guessBestFormat()) { 
        case 'json' :
            return new JsonResponse($location->getId(), 201);
        
        default :
    }
    
    return $app->redirect('/locations');
});

/**
 * Put
 */
$app->put('/locations/(\d+)', function (Request $request, $id) use ($app, $con) { 
    $name = $request->getParameter("name", "POST");  

    $mapper = new LocationDataMapper($con);
    $loc = new LocationFinder($con);
    $city = $loc->findOneById($id);
    $city->setName($name);

    if(false === isset($city)) {
        throw new NotFoundException('City not found !');
    }

    if(true === empty($name)) {
        throw new HttpException(400, 'Name cannot be empty !');
    }

    $mapper->persist($city);

    switch($request -> guessBestFormat()) {
        case 'json' :
            return new JsonResponse($city);

        default :
    }
            
    return $app->redirect('/locations/'.$id);
});

/**
 * Delete
 */
$app->delete('/locations/(\d+)', function (Request $request, $id) use ($app, $con) {
    $loc = new LocationFinder($con);
    $mapper = new LocationDataMapper($con);

    $city = $loc->findOneById($id);
 
    if(false === isset($city)) {
        throw new NotFoundException('City not found !');
    }

    $mapper->remove($city);
    
    switch($request -> guessBestFormat()) {
        case 'json' :
            return new JsonResponse($id, 204);

        default :
    }
    
    return $app->redirect('/locations');
});

return $app;
