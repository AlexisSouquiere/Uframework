<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>All cities</title>
    </head>
    <body>
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
            <input type="hidden" name="_method" value="POST">
            <input type="submit" value="Add New">       
        </form>
    </body>
</html>
