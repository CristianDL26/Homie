<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}include 'db_connection.php';

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
    <script src="script/check_password.js" defer></script>
    <script src="script/animations.js" defer></script>
</head>

<body style="background-color: #A8DADC;">
    <div class="login-container">
        <a href="index.php" class="homie-logo">
            <img class="logo" src="img/logo_new.png">
        </a>
        <form id="resetPasswordForm" method="POST" action="password_reset.php">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <form class="form" style="height: 15vw">
                <p class="form-title ">Reimposta la password</p>
                <div class="input-container">
                    <label for="New Password">Nuova Password </label>
                    <input class="input-field" type="password" name="new_password" id="password" required>
                </div>
                <div class="input-container">
                    <label for="confirm_password">Conferma Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required onkeyup="checkPassword()">
                </div>
                <button type="submit" class="login-button">
                    Invia
                </button>
            </form>
        </form>
</body>