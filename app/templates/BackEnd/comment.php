<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Comments</title>
        <link href="../../css/bootstrap.min.css" rel="stylesheet" media="screen">
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
            <h1><?php echo $comment->getUsername(); ?></h1>

             <form action="/admin/comments/<?= $comment->getId(); ?>" method="POST">
                 <input type="hidden" name="_method" value="PUT">

                 <label for="body">Comment Body : </label>
                 <textarea rows="3" name="body"><?= $comment->getBody(); ?></textarea><br/>

                 <input type="submit" value="Update" class="btn btn-warning">
             </form>
             <a href="/admin/comments"><input type="submit" value="Cancel" class="btn btn-danger"></a>
        </div>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
    </body>
</html>
