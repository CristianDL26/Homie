<?php
include 'db_connection.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}if (isset($_SESSION['userid'])) {
    header('Location: home_pro.php');
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
    <script src="script/upload-area.js" defer></script>
    <script src="script/check_password.js" defer></script>
</head>

<body style="background-color: #A8DADC;">
    <div class="login-container">
        <a href="index.php" class="homie-logo">
            <img class="logo" src="img/logo_new.png">
        </a>
        <form class="form" style="width: 50vh;" action="pro_insert.php" method="POST" enctype="multipart/form-data">
            <p class="form-title">Registrati come professionista</p>

            <?php if (isset($_GET['error'])) { ?>
                <p class="error" style="color: red;"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            <div class="input-container">
                <label for="nome">Nome</label>
                <input class="input-field" type="text" placeholder="Enter Name" required="" id="nome" name="nome">
            </div>
            <div class="input-container">
                <label for="cognome">Cognome</label>
                <input class="input-field" type="text" placeholder="Enter Surname" required="" id="cognome" name="cognome">
            </div>
            <div class="input-container">
                <label for="email">Email</label>
                <input class="input-field" type="email" placeholder="Enter email" required="" id="email" name="email">
            </div>
            <div class="input-container">
                <label for="piva">PIVA</label>
                <input class="input-field" type="text" placeholder="Enter P.IVA" required="" id="piva" name="piva">
            </div>
            <div class="input-container">
                <label for="indirizzo">Indirizzo</label>
                <input class="input-field" type="text" placeholder="Enter Address" required="" id="indirizzo" name="indirizzo">
            </div>
            <div class="input-container">
                <span>
                    Professione
                </span>
                <select class="input-field" required="" id="professione" name="professione">
                    <option value="" disabled selected>Seleziona il tuo ruolo</option>
                    <option value="elettricista">Elettricista</option>
                    <option value="idraulico">Idraulico</option>
                    <option value="colf">Colf</option>
                    <option value="pittore">Pittore</option>
                    <option value="fabbro">Fabbro</option>
                    <option value="tuttofare">Tuttofare</option>
                </select>
            </div>
            <div class="input-container">
                <label for="prezzo-orario">Prezzo Orario</label>
                <input class="input-field" type="number" placeholder="Enter Prezzo Orario" required="" id="p_orario" name="p_orario">
            </div>
            <div class="input-container">
                <label for="prezzo-chiamata">Prezzo per chiamata</label>
                <input class="input-field" type="number" placeholder="Enter Prezzo chiamata" required="" id="p_chiamata" name="p_chiamata">
            </div>
            <div class="input-container">
                <label for="password">Password</label>
                <input class="input-field" type="password" placeholder="Enter password" required="" id="password" name="password">
            </div>
            <div class="input-container">
                <label for="confirm_password">Conferma password</label>
                <input class="input-field" type="password" placeholder="Confirm password" required="" id="confirm_password" name="confirm_password" onkeyup="checkPassword()">

            </div>
            <div>
            <p id="password_error" style="color: red;"></p>
            </div>
            <div class="upload-area" id="uploadArea" style="margin-top: 2vh;">
            <input type="file" id="fileInput" accept="image/*" required name="photo"/>
            <label for="fileInput">Trascina qui o seleziona l'immagine del profilo</label>
            </div>
            <div class="input-container" style="margin-top: 2vh;">
                <input type="submit" class="login-button" value="Registrati" disabled></input>
            </div>
            <p class="signup-link">
                Già registrato?
                <a href="login_page_pro.php">Accedi</a>
            </p>
        </form>
    </div>
</body>