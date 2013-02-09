<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>All cities</title>
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    </head>
    <body>
        <div class="container">
            <h1>All cities</h1>
            <table>
            <?php foreach ($cities as $city) : ?>
                <tr>
                    <td>
                        <a href="/locations/<?php echo $city->getId(); ?>"><?php echo $city->getName(); ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </table>
            <form action="/locations" method="POST">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name">
                <input type="submit" value="Add New" class="btn btn-primary">       
                <input type="hidden" name="_method" value="POST">
            </form>
        </div>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
