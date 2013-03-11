<?php

use Http\Request;
use Model\DataMapper\CommentDataMapper;
use Model\Finder\CommentFinder;
use Model\Finder\LocationFinder;
use Model\Entities\Comment;
use Model\Flash;
use Http\JsonResponse;
use Exception\HttpException;
use Exception\NotFoundException;

/**
 * Get Comments
 */
$app->get('/admin/comments', function (Request $request) use ($app, $con) {
    $criterias = getCriterias($request);

    $com = new CommentFinder($con);
    $comments = $com->findAll($criterias);

    if (!isset($comments)) {
        throw new NotFoundException('No comments founded !');
    }

    return $app->render('BackEnd/comments.php', ['comments' => $comments]);
});

/**
 * Get Comment
 */
$app->get('/admin/comments/(\d+)', function (Request $request, $id) use ($app, $con) {
    $criterias = getCriterias($request);

    $com = new CommentFinder($con);
    $comment = $com->findOneById($id);

    if (!isset($comment)) {
        throw new NotFoundException('No comments founded !');
    }

    return $app->render('BackEnd/comment.php', ['comment' => $comment]);
});

/**
 * Get Comments' Locations
 */
$app->get('/locations/(\d+)/comments', function (Request $request, $id) use ($app, $con) {
    $com = new CommentFinder($con);
    $comments = $com->findCommentsByLocationId($id);

    if (!isset($city)) {
        throw new NotFoundException('No comments founded !');
    }

    return new JsonResponse($comments);
});

/**
 * Post a comment for a location
 */
$app->post('/locations/(\d+)/comments', function (Request $request, $id) use ($app, $con) {
    $username = $request->getParameter('username', 'POST');
    $body = $request->getParameter('comment', 'POST');

    $loc = new LocationFinder($con);
    $location = $loc->findOneByIdJustLocation($id);

    if (!isset($location)) {
        throw new NotFoundException(404, 'Location not found !');
    }

    if (empty($username) || empty($body)) {
        throw new HttpException(400, 'Please completed all required fields.');
    }

    $com = new CommentDataMapper($con);
    $comment = new Comment($username, $body, new \DateTime());
    $comment->setLocation($location);

    $com->persist($comment);

    switch ($request -> guessBestFormat()) {
        case 'json' :
            return new JsonResponse($comment->getId(), 201);

        default :
    }

    return $app->redirect('/locations/'.$id);
});

/*
 * Put a comment
 */
$app->put('/admin/comments/(\d+)', function (Request $request, $id) use ($app, $con) {
    $body = $request->getParameter('body', 'POST');

    if (empty($body)) {
        throw new HttpException(400, 'Please completed all required fields.');
    }

    $comment = new CommentFinder($con);
    $com = $comment->findOneById($id);
    $com->setBody($body);

    $comment = new CommentDataMapper($con);
    $comment->persist($com);

    new Flash('confirmation', "The comment has been successfully updated !");

    return $app->redirect('/admin/comments');
});

/**
 * Delete a Party - BackEnd
 */
$app->delete('/admin/comments/(\d+)', function (Request $request, $id) use ($app, $con) {
    $com = new CommentFinder($con);
    $mapper = new CommentDataMapper($con);

    $comment = $com->findOneById($id);

    if (false === isset($comment)) {
        throw new NotFoundException('Comment not found !');
    }

    $mapper->remove($comment);

    new Flash('confirmation', "The comment has been successfully deleted !");

    return $app->redirect('/admin/comments');
});
