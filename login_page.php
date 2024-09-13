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
    <script src="script/maps.js" defer></script>
    <script src="script/animations.js" defer></script>
</head>

<body style="background-color: #A8DADC;">
    <div class="login-container">
        <a href="index.php" class="homie-logo">
            <img class="logo" src="img/logo_new.png">

        </a>
        <form class="form" style="height: 35vw; width:50vh" action="user_login.php" method="POST">
            <p class="form-title">Accedi</p>
            <?php if (isset($_GET['error'])) { ?>
                <p class="error" style="color: red;"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            <?php if (isset($_GET['success'])) { ?>
                <?php if ($_GET['success'] == 'true') { ?>
                    <p style="color: #1D3557;">Password cambiata con successo</p>
                <?php } else {?>
                    <p  style="color: red;">Errore nel cambio password</p>
                <?php }?>
                
            <?php } ?>
            <div class="input-container">
                <label>Email </label>
                <input id="abc" class="input-field" type="email" placeholder="Enter email" required="" name="email" id="email">
            </div>
            <div class="input-container">
                <label>Password</label>
                <input class="input-field" type="password" placeholder="Enter password" required="" name="password" id="password">
            </div>
            <p class="forgot-password"><a href="forgot_password.php">Password dimenticata?</a></p>
            <button type="submit" class="login-button">
                Accedi
            </button>

            <p class="signup-link">
                <a href="login_github.php">Accedi con Google</a>
            </p>

            <p class="signup-link">
                Non hai un account?
                <a href="register_page.php">Registrati</a>
            </p>
<!--             <p style="text-align: center;">oppure</p>
            <p class="google-signup">
                <button class="google-button login-button">
                    <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" viewBox="0 0 256 262">
                        <path fill="#4285F4" d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027"></path>
                        <path fill="#34A853" d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1"></path>
                        <path fill="#FBBC05" d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782"></path>
                        <path fill="#EB4335" d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251"></path>
                    </svg>
                    Continua con Google
                </button> -->

            <p class="signup-link">
                Sei un professionista?
                <a href="login_page_pro.php">Accedi qui</a>
            </p>
        </form>
    </div>
</body>
