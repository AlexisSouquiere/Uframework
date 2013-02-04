<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?php echo $location; ?></title>
    </head>
    <body>
        <h1><?php echo $location ?></h1>
        <form action="/locations/<?= $id ?>" method="POST">
           <input type="hidden" name="_method" value="PUT">
           <input type="text" name="name" value="<?= $location ?>">
           <input type="submit" value="Update">
        </form>
        <form action="/locations/<?= $id ?>" method="POST">
           <input type="hidden" name="_method" value="DELETE">
           <input type="submit" value="Delete">
        </form>
        <?php foreach ($comments as $comment) { ?>
           <div>
                <?php echo $comment->getUsername(); ?> - <?php echo $comment->getCreatedAt(); ?>
                <p><?php echo $comment->getBody(); ?></p>
           </div>
        <?php } ?>
        <footer>
            <a href="/locations">Back to list</a>
        </footer>
    </body>
</html>
