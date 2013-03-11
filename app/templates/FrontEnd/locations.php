<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>All locations</title>
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
            <div class="row">
                <div class="span6">
                    <h2>Find a location</h2>
                    <form action="/locations" method="GET">
                        <input type="text" name="value" />
                        <select name="field">
                            <option value="LOCATIONS.name">Name</option>
                            <option value="LOCATIONS.address">Address</option>
                        </select>
                        <input type="submit" class="btn btn-primary" value="Search"/>
                    </form>
                    <h1>All locations</h1>
                    <table>
                    <?php foreach ($locations as $location) : ?>
                        <tr>
                            <td>
                                <a href="/locations/<?= $location->getId(); ?>"><?= $location->getName(); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </table>
                </div>

                <div class="span6">
            <h2>New location</h2>
            <form action="/locations" method="POST">
                <label for="name">Name : </label>
                <input type="text" name="name" id="name" />

                <label for="name">Address : </label>
                <input type="text" name="address" id="address" />

                <label for="name">Description : </label>
                <textarea rows="3" name="description"></textarea><br/>

                <label for="name">Phone number : </label>
                <input type="text" name="phone_number" id="phone" />

                <input type="submit" value="Add New" class="btn btn-primary" />
                <input type="hidden" name="_method" value="POST">
            </form>
                </div>
            <a href="/">Back to home</a>
        </div>

        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
