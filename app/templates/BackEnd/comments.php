<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Comments</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
    </head>
    <body>

<?php include('header.php'); ?>

        <div class="container">
            <?php $flash = new Model\Flash();
            $message = $flash->getMessage('confirmation');
            if (isset($message)) : ?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong><?= $message ; ?></strong>
            </div>
            <?php endif; ?>

            <h2>Find a comment</h2>
            <form action="/admin/comments" method="GET">
                <input type="text" name="value" />
                <select name="field">
                    <option value="COMMENTS.username">Username</option>
                    <option value="LOCATIONS.name">Place</option>
                </select>
                <input type="submit" class="btn btn-primary" value="Search"/>
            </form>

            <h1>All Comments</h1>
            <table class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Username</td>
                        <td>Location</td>
                    </tr>
                </thead>
            <?php foreach ($comments as $comment) : ?>
                <tr>
                    <td>
                        <p><?= $comment->getId(); ?></p>
                    </td>
                    <td>
                        <p><?= $comment->getUsername(); ?></p>
                    </td>
                    <td>
                        <a href="/admin/locations/<?= $comment->getLocation()->getId() ?>"><p><?= $comment->getLocation()->getName(); ?></p></a>
                    </td>
                    <td>
                        <form action="/admin/comments/<?= $comment->getId(); ?>" method="GET">
                            <input type="submit" value="Update" class="btn btn-warning"></a>
                        </form>
                    </td>
                    <td>
                        <form action="/admin/comments/<?= $comment->getId(); ?>" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-danger" >
                        </form>
                    </td>
               </tr>
            <?php endforeach; ?>
            </table>
        </div>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>
