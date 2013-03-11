<?php

use Model\Finder\LocationFinder;
use Model\Finder\PartyFinder;
use Http\Request;
use Http\JsonResponse;
use Exception\HttpException;
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

$app->addListener('process.before', function(Request $request) use ($app) {
    session_start();

    $allowed = [
        '/' => [ Request::GET ],
        '/login' => [ Request::GET, Request::POST ],
        '/locations' => [ Request::GET, Request::POST ],
        '/locations/(\d+)' => [ Request::GET ],
        '/locations/(\d+)/comments' => [ Request::GET, Request::POST ],
        '/locations/(\d+)/parties' => [ Request::GET, Request::POST ],
        '/parties' => [ Request::GET ],
        '/parties/(\d+)' => [ Request::GET ],
    ];

    if (isset($_SESSION['is_authenticated'])
        && true === $_SESSION['is_authenticated']) {
        return;
    }

    foreach ($allowed as $uri => $methods) {
        if (preg_match(sprintf('#^%s$#', $uri), $request->getUri())
                && in_array($request->getMethod(), $methods)) {
                return;
        }
    }

    switch ($request->guessBestFormat()) {
        case 'json':
            throw new HttpException(401);
    }

    return $app->redirect('/login');
});

$app->get('/login', function () use ($app) {
    if (!isset($_SESSION['is_authenticated']) || false === $_SESSION['is_authenticated']) {
        return $app->render('FrontEnd/login.php');
    }
    $app->redirect('/');
});

$app->post('/login', function (Request $request) use ($app) {
    $user = $request->getParameter('user');
    $pass = $request->getParameter('password');

    if ('admin' === $user && 'admin' === $pass) {
        $_SESSION['is_authenticated'] = true;

        return $app->redirect('/');
    }

    return $app->render('FrontEnd/login.php', [ 'user' => $user ]);
});

$app->get('/logout', function (Request $request) use ($app) {
    session_destroy();

    return $app->redirect('/');
});

/**
 * Index - FrontEnd
 */
$app->get('/', function (Request $request) use ($app, $con) {
    $loc = new LocationFinder($con);
    $par = new PartyFinder($con);
    $date = new \DateTime();
    $locations = $loc->findAllJustLocations(['order by' => 'CREATED_AT DESC', 'limit' => '0, 5']);
    $parties = $par->findAll(['where' => 'START_AT > "'.$date->format('Y-m-d H:i:s').'"','order by' => 'START_AT ASC', 'limit' => '0, 5']);

    switch ($request->guessBestFormat()) {
        case 'json' :
            return new JsonResponse([$locations, $parties]);

        default :
    }

    return $app->render('FrontEnd/index.php', ['locations' => $locations, 'parties' => $parties]);
});

/**
 * Index - BackEnd
 */
$app->get('/admin', function (Request $request) use ($app, $con) {
     return $app->render('BackEnd/index.php');
});

require __DIR__.'/CommentController.php';
require __DIR__.'/PartyController.php';
require __DIR__.'/LocationController.php';

function getCriterias(Request $request)
{
    $limit = $request->getParameter('limit');
    $order = $request->getParameter('orderBy');
    $field = $request->getParameter('field');
    $value = $request->getParameter('value');

    $lim = null;
    if (isset($limit)) {
        $lim = '0, ' . $limit;
    }

    $where = null;
    if (isset($field) && isset($value)) {
        $where = $field . ' LIKE "%'.$value.'%"';
    }

    return ['where' => $where, 'order by' => $order, 'limit' => $lim];
}

return $app;
