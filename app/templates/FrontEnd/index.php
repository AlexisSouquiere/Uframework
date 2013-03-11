<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Index</title>
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
            <div class="hero-unit">
                <h1>Welcome !</h1>
                <p></p>
            </div>
            <div class="row">
                <div class="span6">
                    <h2>Last five places added</h2>
                    <?php foreach ($locations as $location) { ?>
                    <div>
                        <a href="/locations/<?php echo $location->getId(); ?>"><?= $location->getName() . ' - ' . $location->getAddress() ?></a>
                    </div>
                    <?php } ?>
                </div>

                <div class="span6">
                    <h2>Next parties</h2>
                    <?php foreach ($parties as $party) { ?>
                    <div>
                        <a href="/parties/<?php echo $party->getId(); ?>"><?= $party->getName() . ' - ' . $party->getStartAt()->format('Y-m-d H:i:s') ?></a>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <hr/>
            <div class="btn_list">
                <a href="/locations">See all places</a> | <a href="/parties">See all parties</a>
            </div>
        </div>
    </div>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>
