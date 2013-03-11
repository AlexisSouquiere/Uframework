<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Login</title>
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            .container {
                width: 300px;
            }
        </style>
    </head>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand" href="/">Uframework</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li><a href="/">Home</a></li>
                            <li><a href="/locations?orderBy=name">Locations</a></li>
                            <li><a href="/parties?orderBy=start_at">Parties</a></li>
                            <li><a href="/admin">Backend</a></li>
                            <li class="active"><a href="/login">Login</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <form class="form-signin" action="/login" method="POST">
                <h2 class="form-signin-heading">Please Login</h2>
                <input type="text" name="user" class="input-block-level" placeholder="Username">
                <input type="password" name="password" class="input-block-level" placeholder="Password">
                <input type="hidden" name="_method" value="POST">
                <label class="checkbox">
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
                <button class="btn btn-large btn-primary" type="submit">Login</button>
            </form>
        </div>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
