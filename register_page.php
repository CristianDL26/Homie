<?php
include 'db_connection.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}if (isset($_SESSION['userid'])) {
    header('Location: home.php');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Landing</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="icon" href="img/icons/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="img/icons/favicon.ico" type="image/x-icon">
    <script src="script/autocomplete.js" defer></script>
    <script src="script/animations.js" defer></script>
    <script src="script/check_password.js" defer></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8NKq8Y7ZWcdnprjHHsH153OrNT3HyVmk"></script>

</head>

<body style="background-color: #A8DADC;">
    <div class="login-container">
        <a href="index.php" class="homie-logo">
            <img class="logo" src="img/logo_new.png">

        </a>
        <form class="form" style="width: 50vh;" action="user_insert.php" method="POST">
            <p class="form-title">Registrati</p>
            <?php if(isset($_GET['error'])) { ?>
                <p class="error" style="color: red;"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            <p class="input-container">
                <label for="nome">Nome</label>
                <input class="input-field" type="text" placeholder="Enter Name" required="" id="nome" name="nome">
            </p>
            <div class="input-container">
                <label for="cognome">Cognome</label>
                <input class="input-field" type="text" placeholder="Enter Surname" required="" id="cognome" name="cognome">
            </div>
            <div class="input-container">
                <label for="email">Email</label>
                <input class="input-field" type="email" placeholder="Enter email" required="" id="email" name="email" >
            </div>
            <div class="input-container">
                <label for="indirizzo">Indirizzo</label>
                <input class="input-field" type="text" placeholder="Enter Address" required="" id="address" name="indirizzo" onkeyup="handleInputB()">
                
            </div>
            <ul id="resultsContainer"></ul>
            <div class="input-container">
                <label for="password">Password</label>
                <input class="input-field" type="password" placeholder="Enter password" required="" id="password" name="password" >
            </div>
            <div class="input-container">
                <label for="confirm_password">Conferma password</label>
                <input class="input-field" type="password" placeholder="Confirm password" required="" id="confirm_password" name="confirm_password" onkeyup="checkPassword()">

            </div>
            <p id="password_error" style="color: red;"></p>
            <div class="input-container" style="margin-top: 10%;">
                <input type="submit" class="login-button" value="Registrati" disabled></input>
            </div>
            <p class="signup-link">
                Già registrato?
                <a href="login_page.php">Accedi</a>
            </p>
        </form>
    </div>
    <div class="separator"></div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var indirizzoValue = localStorage.getItem('insert-address');
            if (indirizzoValue) {
                document.getElementById('address').value = indirizzoValue;
                localStorage.removeItem('address'); 
            }
        });
    </script>
</body>