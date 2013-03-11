<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?= $party->getName() ?></title>
        <link href="../../css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="../../css/datepicker.css" rel="stylesheet">
    <link href="../../css/bootstrap-timepicker.min.css" rel="stylesheet"/>
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
            <h1><?= $party->getName(); ?></h1>

            <form action="/admin/parties/<?= $party->getId(); ?>" method="POST">
                <input type="hidden" name="_method" value="PUT">

                <label for="name">Name : </label>
                <input type="text" name="name" id="name" value="<?= $party->getName(); ?>">

        <label for="startAt">Start At : </label>
                <div class="input-append date" id="dp3" data-date="<?= $party->getStartAt()->format('Y-m-d') ?>" data-date-format="yyyy-mm-dd">
                    <input class="span2" size="16" type="text" value="<?= $party->getStartAt()->format('Y-m-d') ?>" name="date"/>
                    <span class="add-on"><i class="icon-calendar"></i></span>
                </div>
                <div class="input-append bootstrap-timepicker" >
                    <input id="timepicker2" class="span2" type="text" name="hour" value="<?= $party->getStartAt()->format('H:i:s') ?>"></input>
                    <span class="add-on"><i class="icon-time"></i></span>
                </div>

                <label for="name">Description : </label>
                <textarea rows="3" name="description"><?= $party->getDescription(); ?></textarea><br/>

                <input type="submit" value="Update" class="btn btn-warning">
                <a href="/admin/parties" class="btn btn-danger">Cancel</a>
             </form>

        </div>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/bootstrap-datepicker.js"></script>

    <script type="text/javascript" src="../../js/bootstrap-timepicker.js"></script>
    <script type="text/javascript">
        $('#dp3').datepicker();
        $('#timepicker2').timepicker({
            minuteStep: 1,
            template: 'dropdown',
            showSeconds: true,
            showMeridian: false,
        });
    </script>
    </body>
</html>
