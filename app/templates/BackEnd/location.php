<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?= $location->getName() ?></title>
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
            <h1><?php echo $location->getName(); ?></h1>

            <form action="/admin/locations/<?= $location->getId(); ?>" method="POST">
                <input type="hidden" name="_method" value="PUT">

                <label for="name">Name : </label>
                <input type="text" name="name" id="name"value="<?= $location->getName(); ?>">

                <label for="name">Address : </label>
                <input type="text" name="address" id="address" value="<?= $location->getAddress(); ?>">

                <label for="name">Phone number : </label>
                <input type="text" name="phone_number"value="<?= $location->getPhoneNumber(); ?>">

                <label for="name">Description : </label>
                <textarea rows="3" name="description"><?= $location->getDescription(); ?></textarea><br/>

                <input type="submit" value="Update" class="btn btn-warning">
                <a href="/admin/locations" class="btn btn-danger">Cancel</a>
             </form>

        </div>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
    </body>
</html>
