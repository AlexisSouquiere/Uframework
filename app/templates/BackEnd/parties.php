<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Parties</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
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

            <h2>Find a party</h2>
            <form action="/admin/parties" method="GET">
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
                        <td>Place</td>
                        <td>Start At</td>
                    </tr>
                </thead>
            <?php foreach ($parties as $party) : ?>
                <tr>
                    <td>
                        <p><?= $party->getId(); ?></p>
                    </td>
                    <td>
                        <p><?= $party->getName(); ?></p>
                    </td>
                    <td>
                        <a href="/admin/locations/<?= $party->getLocation()->getId() ?>"<p><?= $party->getLocation()->getName(); ?></p>
                    </td>
                    <td>
                        <p><?= $party->getStartAt()->format('Y-m-d H:i:s'); ?></p>
                    </td>
                    <td>
                        <form action="/admin/parties/<?= $party->getId(); ?>" method="GET">
                            <input type="submit" value="Update" class="btn btn-warning"></a>
                        </form>
                    </td>
                    <td>
                        <form action="/admin/parties/<?= $party->getId(); ?>" method="POST">
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
