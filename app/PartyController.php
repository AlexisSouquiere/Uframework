<?php

use Http\Request;
use Model\Finder\PartyFinder;
use Model\Finder\LocationFinder;
use Model\DataMapper\PartyDataMapper;
use Model\Entities\Party;
use Model\Flash;
use Http\JsonResponse;
use Exception\HttpException;
use Exception\NotFoundException;

/**
 * Parties - FrontEnd
 */
$app->get('/parties', function (Request $request) use ($app, $con) {
    $criterias = getCriterias($request);

    $par = new PartyFinder($con);
    $parties = $par->findAll($criterias);

    switch ($request->guessBestFormat()) {
        case 'json' :
            return new JsonResponse($parties);

        default :
    }

    return $app->render('FrontEnd/parties.php', ['parties' => $parties]);
});

/**
 * Parties - BackEnd
 */
$app->get('/admin/parties', function (Request $request) use ($app, $con) {
    $criterias = getCriterias($request);

    $par = new PartyFinder($con);
    $parties = $par->findAll($criterias);

    return $app->render('BackEnd/parties.php', ['parties' => $parties]);
});

/*
 * Get a Party - FrontEnd
 */
$app->get('/parties/(\d+)', function (Request $request, $id) use ($app, $con) {
    $par = new PartyFinder($con);
    $party = $par->findOneById($id);

    if (false === isset($party)) {
        throw new NotFoundException('Party not found !');
    }

    switch ($request -> guessBestFormat()) {
        case 'json' :
            return new JsonResponse($party);

        default :
    }

    return $app->render('FrontEnd/party.php', ['party' => $party]);

});

/*
 * Get a Party - BackEnd
 */
$app->get('/admin/parties/(\d+)', function (Request $request, $id) use ($app, $con) {
    $par = new PartyFinder($con);
    $party = $par->findOneById($id);

    if (false === isset($party)) {
        throw new NotFoundException('Party not found !');
    }

    return $app->render('BackEnd/party.php', ['party' => $party]);

});

/*
 * Post a party
 */
$app->post('/locations/(\d+)/parties', function (Request $request, $id) use ($app, $con) {
    $name = $request->getParameter('name', 'POST');
    $description = $request->getParameter('description', 'POST');

    $date = $request->getParameter('date', 'POST');
    $time = $request->getParameter('hour', 'POST') . ':' . $request->getParameter('minute', 'POST') . ':' . $request->getParameter('second', 'POST');
    $start = $date.' '.$time;

    $loc = new LocationFinder($con);
    $location = $loc->findOneByIdJustLocation($id);

    if (!isset($location)) {
        throw new NotFoundException(404, 'Location not found !');
    }

    if (empty($name) || empty($start)) {
        throw new HttpException(400, 'Please completed all required fields.');
    }

    $format = 'Y-m-d H:i:s';
    $start = DateTime::createFromFormat($format, $start);

    $par = new PartyDataMapper($con);
    $party = new Party($name, $start, $description, new \DateTime());
    $party->setLocation($location);

    $par->persist($party, array('id' => $id));

    switch ($request -> guessBestFormat()) {
        case 'json' :
            return new JsonResponse($party->getId(), 201);

        default :
    }

    return $app->redirect('/locations/'.$id);
});

/*
 * Put a party
 */
$app->put('/admin/parties/(\d+)', function (Request $request, $id) use ($app, $con) {
    $date = $request->getParameter('date', 'POST');
    $time = $request->getParameter('hour', 'POST') . ':' . $request->getParameter('minute', 'POST') . ':' . $request->getParameter('second', 'POST');
    $start = $date.' '.$time;
    $format = 'Y-m-d H:i:s';
    $start = DateTime::createFromFormat($format, $start);

    $name = $request->getParameter('name', 'POST');
    $description = $request->getParameter('description', 'POST');

    if (empty($name)) {
        throw new HttpException(400, 'Please completed all required fields.');
    }

    $par = new PartyFinder($con);
    $party = $par->findOneById($id);

    $party->setName($name);
    $party->setDescription($description);
    $party->setStartAt($start);

    $par = new PartyDataMapper($con);
    $par->persist($party);

    new Flash('confirmation', "The party has been successfully updated !");

    return $app->redirect('/admin/parties');
});

/**
 * Delete a Party - BackEnd
 */
$app->delete('/admin/parties/(\d+)', function (Request $request, $id) use ($app, $con) {
    $loc = new PartyFinder($con);
    $mapper = new PartyDataMapper($con);

    $party = $loc->findOneById($id);

    if (false === isset($party)) {
        throw new NotFoundException('City not found !');
    }

    $mapper->remove($party);

    new Flash('confirmation', "The party has been successfully deleted !");

    return $app->redirect('/admin/parties');
});
