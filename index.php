<?php
include 'db_connection.php';
session_start();
if (isset($_SESSION['userid'])) {
    header('Location: home.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Homie</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="icon" href="img/icons/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="img/icons/favicon.ico" type="image/x-icon">
    <script src="script/autocomplete.js" defer></script>
    <script src="script/maps.js" defer></script>
    <script src="script/animations.js" defer></script>
</head>
<body>
    <div class="background">
        <?php include 'header.php'; ?>
        <div class="main-page-top">
            <?php include 'landing_search_box.php'; ?>
        </div>
        <div class="main-page-body">
            <?php include 'landing_description.php'; ?>
        </div>
        <div class="mp-footer">
            <?php include 'footer.php'; ?>
        </div>
    </div>
</body>
</html>