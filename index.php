<?php
session_start();
if (!isset($_SESSION["loggedIn"])) {
    header('Location:login.php', true);
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    <link href="css/toastr.min.css" rel="stylesheet"/>
    <style>
        .container {
            height: 80vh;
        }

        .site-header {
            border-bottom: 1px solid #ccc;
            padding: .5em 1em;
            display: flex;
            justify-content: space-between;
        }

        .site-identity h1 {
            font-size: 1.5em;
            margin: .6em 0;
            display: inline-block;
        }

        .site-navigation ul,
        .site-navigation li {
            margin: 0;
            padding: 0;
        }

        .site-navigation li {
            display: inline-block;
            margin: 1.4em 1em 1em 1em;
        }
    </style>
</head>
<body>
<header class="site-header">
    <div class="site-identity">
        <h1><a href="#">Attendance Management</a></h1>
    </div>
    <nav class="site-navigation">
        <ul class="nav">
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>
<div class="container align-content-center d-flex justify-content-center align-items-center">
    <b>Logged in Successfully.....</b>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/toastr.min.js"></script>
</body>
</html>
