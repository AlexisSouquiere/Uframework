<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Locations</title>
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

            <h2>Find a location</h2>
            <form action="/admin/locations" method="GET">
                <input type="text" name="value" />
                <select name="field">
                    <option value="LOCATIONS.name">Name</option>
                    <option value="LOCATIONS.address">Address</option>
                </select>
                <input type="submit" class="btn btn-primary" value="Search"/>
            </form>

            <h1>All Locations</h1>
            <table class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Name</td>
                        <td>Address</td>
                    </tr>
                </thead>
            <?php foreach ($locations as $location) : ?>
                <tr>
                    <td>
                        <p><?= $location->getId(); ?></p>
                    </td>
                    <td>
                        <p><?= $location->getName(); ?></p>
                    </td>
                    <td>
                        <p><?= $location->getAddress(); ?></p>
                    </td>
                    <td>
                        <form action="/admin/locations/<?= $location->getId(); ?>" method="GET">
                            <input type="submit" value="Update" class="btn btn-warning"></a>
                        </form>
                    </td>
                    <td>
                        <form action="/admin/locations/<?= $location->getId(); ?>" method="POST">
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
