<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?php echo $location->getName(); ?></title>
        <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAb_W-cWIqDBpCazQfuhyVo4iIV9CYCdZc&sensor=false"></script>
    <link href="../css/datepicker.css" rel="stylesheet">
    <link href="../css/bootstrap-timepicker.min.css" rel="stylesheet"/>
        <script>
            var map;
            var myLatng;
            function initialize()
            {
                myLatng = new google.maps.LatLng(<?= $geocoder['latitude'] ?>, <?= $geocoder['longitude'] ?>);
                var mapOptions = {
                    zoom: 15,
                    center: myLatng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById('map_canvas'),mapOptions);
                var myMarker = new google.maps.Marker({
                position: myLatng,
                map: map,
                title: "<?= $location->getName() ?>"
                });
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            #map_canvas {
                width:400px;
                height:300px;
            }
        </style>
    </head>
    <body>

<?php include('header.php'); ?>

        <div class="container">
            <div class="row">
                <div class="span6">
                    <h1><?= $location->getName(); ?></h1>
                    <?php if($location->getCreatedAt() != null) : ?>
                    <p>Created on <?= $location->getCreatedAt()->format('Y-m-d H:i:s'); ?></p>
                    <?php endif; ?>
                    <p>Address : <?= $location->getAddress(); ?></p>
                    <p><?= $location->getDescription(); ?></p>
                    <?php if($location->getPhoneNumber() != null) : ?>
                    <p>Phone nuber : <?= $location->getPhoneNumber(); ?></p>
                    <?php endif; ?>
                </div>
                <div class="span6">
                    <div id="map_canvas"></div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="span6">
                    <h2>Add a new comment</h2>
                    <form action="/locations/<?= $location->getId(); ?>/comments" method="POST">
                        <label for="username">Username : </label>
                        <input type="text" name="username">
                        <label for="comment">Comment : </label>
                        <input type="text" name="comment">
                        <input type="submit" value="Add" class="btn btn-primary">
                        <input type="hidden" name="_method" value="POST">
                    </form>
                    <?php foreach ($location->getComments() as $comment) : ?>
                    <div>
                        <?= $comment->getUsername(); ?> - <?= $comment->getCreatedAt()->format('Y-m-d H:i:s'); ?>
                        <p><?= $comment->getBody(); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="span6">
                    <h2>Add a new party</h2>
                    <form action="/locations/<?= $location->getId(); ?>/parties" method="POST">
                        <label for="name">Name : </label>
                        <input type="text" name="name">

            <?php $date = new \DateTime() ?>
            <label for="startAt">Start At : </label>
                        <div class="input-append date" id="dp3" data-date="<?= $date->format('Y-m-d')?>" data-date-format="yyyy-mm-dd">
                            <input class="span2" size="16" type="text" value="<?= $date->format('Y-m-d')?>" name="date"/>
                            <span class="add-on"><i class="icon-calendar"></i></span>
                        </div>
                        <div class="input-append bootstrap-timepicker" >
                            <input id="timepicker2" class="span2" type="text" name="hour"></input>
                            <span class="add-on"><i class="icon-time"></i></span>
                        </div>

                        <label for="description">Description : </label>
                        <textarea rows="3" name="description"></textarea><br/>
                        <input type="submit" value="Add" class="btn btn-primary">
                       <input type="hidden" name="_method" value="POST">
                    </form>
                    <?php foreach ($location->getParties() as $party) : ?>
                        <a href="/parties/<?php echo $party->getId(); ?>"><?php echo $party->getName();?> - <?php echo $party->getStartAt()->format('Y-m-d H:i:s'); ?></a><br/>
                    <?php endforeach; ?>
                </div>
            </div>
            <footer>
                <a href="/locations">Back to locations list</a>
            </footer>
        </div>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap-datepicker.js"></script>

    <script type="text/javascript" src="../js/bootstrap-timepicker.js"></script>
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
