<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?php echo $location; ?></title>
        <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
    </head>
    <body>
        <div class="container">
           <h1><?php echo $location; ?></h1>
           <?php if(null !== $createdAt) { ?>
           <p><?php echo $createdAt->format('Y-m-d H:i:s'); } ?></p>
           <form action="/locations/<?= $id; ?>" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="text" name="name" value="<?= $location; ?>">
                <input type="submit" value="Update" class="btn btn-warning">
           </form>
           <form action="/locations/<?= $id; ?>" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                <input type="submit" value="Delete" class="btn btn-danger" >
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
        </div>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>
