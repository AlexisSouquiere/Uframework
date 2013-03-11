<?php

use Http\Request;
use Model\Finder\LocationFinder;
use Model\DataMapper\LocationDataMapper;
use Model\Entities\Location;
use Model\Flash;
use Http\JsonResponse;
use Exception\HttpException;
use Exception\NotFoundException;

/**
 * Get all Locations - FrontEnd
 */
$app->get('/locations', function (Request $request) use ($app, $con) {
    $criterias = getCriterias($request);

    $loc = new LocationFinder($con);
    $locations = $loc->findAllJustLocations($criterias);

    switch ($request->guessBestFormat()) {
        case 'json' :
            return new JsonResponse($locations);

        default :
    }

    return $app->render('FrontEnd/locations.php', ['locations' => $locations]);
});

/**
 * Get all Locations - BackEnd
 */
$app->get('/admin/locations', function (Request $request) use ($app, $con) {
    $criterias = getCriterias($request);

    $loc = new LocationFinder($con);
    $locations = $loc->findAll($criterias);

    return $app->render('BackEnd/locations.php', ['locations' => $locations]);
});

/**
 * Get a Location - FrontEnd
 */
$app->get('/locations/(\d+)', function (Request $request, $id) use ($app, $con) {
    $loc = new LocationFinder($con);
    $location = $loc->findOneById($id);

    if (false === isset($location)) {
        throw new NotFoundException('City not found !');
    }

    $adapter  = new \Geocoder\HttpAdapter\BuzzHttpAdapter();
    $geocoder = new \Geocoder\Geocoder();
    $geocoder->registerProviders([new \Geocoder\Provider\GoogleMapsProvider($adapter)]);
    $result = $geocoder->geocode($location->getAddress());

    switch ($request -> guessBestFormat()) {
        case 'json' :
            return new JsonResponse($location);

        default :
    }

    return $app->render('FrontEnd/location.php', ['location' => $location, 'geocoder' => $result]);
});

/**
 * Get a Location - BackEnd
 */
$app->get('/admin/locations/(\d+)', function (Request $request, $id) use ($app, $con) {
    $loc = new LocationFinder($con);
    $location = $loc->findOneById($id);

    if (false === isset($location)) {
        throw new NotFoundException('City not found !');
    }

    switch ($request -> guessBestFormat()) {
        case 'json' :
            return new JsonResponse($location);

        default :
    }

    return $app->render('BackEnd/location.php', ['location' => $location]);
});

/**
 * Post a location - FrontEnd
 */
$app->post('/locations', function (Request $request) use ($app, $con) {
    $name = $request->getParameter("name", "POST");
    $address = $request->getParameter('address', 'POST');
    $description = $request->getParameter('description', 'POST');
    $phone = $request->getParameter('phone_number', 'POST');

    if (empty($name) || empty($address)) {
        throw new HttpException(400, 'Please completed all required fields.');
    }

    $mapper = new LocationDataMapper($con);

    $location = new Location($name, $address, $description, $phone, new \DateTime());

    $mapper->persist($location);

    switch ($request -> guessBestFormat()) {
        case 'json' :
            return new JsonResponse($location->getId(), 201);

        default :
    }

    return $app->redirect('/locations');
});

/**
 * Put a Location - BackEnd
 */
$app->put('/admin/locations/(\d+)', function (Request $request, $id) use ($app, $con) {
    $mapper = new LocationDataMapper($con);
    $loc = new LocationFinder($con);
    $city = $loc->findOneById($id);

    if (!isset($city)) {
        throw new NotFoundException('City not found !');
    }

    $name = $request->getParameter("name", "POST");
    $address = $request->getParameter('address', 'POST');

    if (empty($name) || empty($address)) {
        throw new HttpException(400, 'Please completed all required fields.');
    }

    $city->setName($name);
    $city->setAddress($address);
    $city->setDescription($request->getParameter('description', 'POST'));
    $city->setPhoneNumber($request->getParameter('phone_number', 'POST'));

    $mapper->persist($city);

    new Flash('confirmation', "The location has been successfully updated !");

    return $app->redirect('/admin/locations');
});

/**
 * Delete a Location - BackEnd
 */
$app->delete('/admin/locations/(\d+)', function (Request $request, $id) use ($app, $con) {
    $loc = new LocationFinder($con);
    $mapper = new LocationDataMapper($con);

    $city = $loc->findOneById($id);

    if (false === isset($city)) {
        throw new NotFoundException('City not found !');
    }

    $mapper->remove($city);

    new Flash('confirmation', "The location has been successfully deleted !");

    return $app->redirect('/admin/locations');
});
