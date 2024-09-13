<?php
// Start the session
session_start();
include 'db_connection.php';
if (isset($_SESSION['professione'])) {
    header('Location:home_pro.php');
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Homie - Homepage</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="icon" href="img/icons/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="img/icons/favicon.ico" type="image/x-icon">
    <script src="script/address.js" defer></script>
    <script src="script/autocomplete.js" defer></script>
    <script src="script/maps.js" defer></script>
    <script src="script/map.js" defer></script>
    <script src="script/animations.js" defer></script>
    <script src="script/tracking-map.js" defer></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8NKq8Y7ZWcdnprjHHsH153OrNT3HyVmk"></script>


</head>

<body>
    <div class="background">
        <?php include 'logged_header.php'; ?>
    </div>
    <div class="home-body-wrapper">
        <?php include 'home-body.php'; ?>
    </div>
    <div class="mp-footer">
        <?php include 'footer.php'; ?>
    </div>

</body>

</html>