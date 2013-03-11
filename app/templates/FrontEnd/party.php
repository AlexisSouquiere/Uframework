<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?php echo $party->getName(); ?></title>
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
            <h1><?php echo $party->getName(); ?></h1>
            <?php if($party->getCreatedAt() != null) : ?>
            <p>Created at : <?php echo $party->getCreatedAt()->format('Y-m-d H:i:s'); ?></p>
            <?php endif; ?>
            <p>Start : <?php echo $party->getStartAt()->format('Y-m-d H:i:s'); ?></p>
            <?php if($party->getDescription() != null) : ?>
            <p>Description : <?php echo $party->getDescription(); ?></p>
            <?php endif; ?>

           <footer>
               <a href="/parties">Back to parties list</a>
           </footer>
        </div>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>
