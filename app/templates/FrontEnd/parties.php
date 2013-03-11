<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>All parties</title>
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
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
            <h2>Find a party</h2>
            <form action="/parties" method="GET">
                <input type="text" name="value" />
                <select name="field">
                    <option value="PARTIES.name">Name</option>
                    <option value="PARTIES.start_at">Date</option>
                    <option value="LOCATIONS.NAME">Place</option>
                </select>
                <input type="submit" class="btn btn-primary" value="Search"/>
            </form>
            <h1>All parties</h1>
            <table class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Name</td>
                        <td>Start At</td>
                        <td>Place</td>
                    </tr>
                </thead>
            <?php foreach ($parties as $party) : ?>
                <tr>
                    <td>
                        <?= $party->getId() ?>
                    </td>
                    <td>
                        <a href="/parties/<?= $party->getId(); ?>"><?= $party->getName() ?></a>
                    </td>
                    <td>
                        <?= $party->getStartAt()->format('Y-m-d H:i:s'); ?>
                    </td>
                    <td>
                        <a href="/locations/<?= $party->getLocation()->getId() ?>"><?= $party->getLocation()->getName(); ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </table>
            <a href="/">Back to home</a>
        </div>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
