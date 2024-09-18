<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db_connection.php';
if (isset($_SESSION['professione'])) {
    header('Location:pro_profile.php');
    exit();
}
?>
<!DOCTYPE html>

<head>
    <title>Profilo Utente</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="img/icons/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="img/icons/favicon.ico" type="image/x-icon">
    <script src="script/autocomplete.js" defer></script>
    <script src="script/maps.js" defer></script>
    <script src="script/animations.js" defer></script>
    <script src="script/map.js" defer></script>
    <script src="script/filters.js" defer></script>
    <script src="script/profile-edit.js" defer></script>
    <script src="script/check_password.js" defer></script>
    <script src="script/change_password.js" defer></script>
    <script src="script/address.js" defer></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8NKq8Y7ZWcdnprjHHsH153OrNT3HyVmk&callback=initMap"></script>


    <script>
        function showContent(sectionId, btn) {
            document.getElementById('changePasswordForm').reset();
            document.getElementById('password_error').innerHTML = '';
            document.getElementById('passwordresponseMessage').innerHTML = '';


            var contents = document.getElementsByClassName('page');
            for (var i = 0; i < contents.length; i++) {
                contents[i].classList.remove('active');
            }
            document.getElementById(sectionId).classList.add('active');

            var buttons = document.querySelectorAll('.navigation-bar button');
            buttons.forEach(function (button) {
                button.classList.remove('active-btn');
            });

            btn.classList.add('active-btn');
        }
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const section = urlParams.get('section');

            if (section) {
                const sectionButton = document.querySelector(`.navigation-bar button[onclick="showContent('${section}',this)"]`);
                if (sectionButton) {
                    showContent(section, sectionButton);
                } else {
                    showContent('profile-info', document.querySelector('.navigation-bar button[onclick="showContent(\'profile-info\',this)"]'));
                }

                const newUrl = window.location.origin + window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
            } else {
                showContent('profile-info', document.querySelector('.navigation-bar button[onclick="showContent(\'profile-info\',this)"]'));
            }
        });

        function deletePastRequest(orderId) {
            if (confirm('Sei sicuro di voler eliminare questo ordine?')) {
                fetch('utilities.php?action=deletePastRequest', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ orderId: orderId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('row-order-' + orderId).remove();
                        } else {
                            alert('Errore durante l\'eliminazione dell\'ordine');
                        }
                    })
                    .catch(error => {
                        console.error('Errore:', error);
                    });
            }
        }
    </script>

</head>

<html>

<body>

    <div class="background">
        <?php include 'logged_header.php'; ?>
    </div>
    <?php
    $nome = htmlspecialchars($_SESSION['name']);
    $email = htmlspecialchars($_SESSION['email']);
    $indirizzo = htmlspecialchars($_SESSION['indirizzo']);
    $cognome = htmlspecialchars($_SESSION['cognome']);
    $userId = htmlspecialchars($_SESSION['userid']);
    ?>
    <div class="profile-name">
        <h1>Benvenuto <span style="color: #E63946"><?php echo $nome ?></span> !</h1>
    </div>
    <div class="profile-home-body-wrapper">

        <div class="profile-container fixed-width">

            <div class="navigation-bar">
                <button onclick="showContent('profile-info',this)" class="active-btn">Informazioni Personali</button>
                <button onclick="showContent('change_password',this) ">Cambia Password</button>
                <button onclick="showContent('ordini',this)">I Miei Ordini</button>
                <button onclick="showContent('contact',this)">Contact</button>
            </div>

            <div class="profile-content">

                <div id="profile-info" class="page active">
                    <h1>Informazioni Personali</h1>
                    <form id="profileForm" class="profile-form">
                        <div class="t-rows-container">
                            <div class="input-container">
                                <label for="nome">Nome: </label>
                                <input type="text" id="nome" name="nome" value="<?php echo $nome ?>" disabled>
                            </div>
                            <div class="input-container">
                                <label for="cognome">Cognome:</label>
                                <input type="text" id="cognome" name="cognome" value="<?php echo $cognome ?>"
                                    disabled>
                            </div>
                        </div>
                        <div class="t-rows-container">
                            <div class="input-container">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" value="<?php echo $email ?>" disabled>
                            </div>
                            <div class="input-container">
                                <label for="indirizzo">Indirizzo:</label>
                                <input type="text" id="indirizzo" name="indirizzo" value="<?php echo $indirizzo ?>"
                                    disabled>
                            </div>
                        </div>
                        <div class="button-container">
                            <button type="button" id="editButton">Modifica</button>
                            <button type="submit" id="saveButton" style="display: none;">Salva</button>
                            <button type="button" id="cancelButton" style="display: none;">Annulla</button>
                        </div>
                    </form>
                    <p id="responseMessage"></p>
                </div>
                <div id="ordini" class="page orders-container">
                    <h1>I Miei Ordini</h1>
                    <?php
                    $query = "SELECT distinct * FROM homie.orders JOIN homie.pro_data WHERE user_id = '$userId' AND pro_id = piva ORDER BY date DESC";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $pro_id = htmlspecialchars($row['pro_id']);
                            $pro_name = htmlspecialchars($row['nome']);
                            $orderDate = htmlspecialchars($row['date']);
                            $details = htmlspecialchars($row['details']);
                            $orderId = htmlspecialchars($row['order_id']);

                            if (
                                htmlspecialchars(
                                    $row['accepted']
                                ) == 0
                            ) {
                                $completed = "Non accettato";
                            } else if (
                                htmlspecialchars(
                                    $row['completed']
                                ) == 0
                            ) {
                                $completed = "Accettato In attesa";
                            } else {
                                $completed = "Completato";
                            }

                            echo '<div id="row-order-' . $orderId . '" class="order">';
                            echo '<button class="delete-order" onclick="deletePastRequest(\'' . $orderId . '\')">X</button>';
                            echo '<p>Professionista: ' . $pro_name . '</p>';
                            echo '<p>Data: ' . $orderDate . '</p>';
                            echo '<p>Dettagli: ' . $details . '</p>';
                            echo '<p>Stato: ' . $completed . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No orders found.</p>';
                    }

                    ?>
                </div>
                <div id="contact" class="page">
                    <h1>Contact</h1>
                    <div class="contact-content">
                        <p>help@homie.it<br>Via luigi vittorio bertarelli 131, Roma (RM), 00159, IT</p>
                    </div>
                </div>
                <div id="change_password" class="page">
                    <h1>Cambia Password</h1>
                    <form id="changePasswordForm" class="change-password-form" style="width: 50%;">
                        <div class="input-container">
                            <label for="old_password">Vecchia Password:</label>
                            <input type="password" id="old_password" name="old_password" required>
                        </div>
                        <div class="input-container">
                            <label for="new_password">Nuova Password:</label>
                            <input type="password" id="password" name="new_password" required>
                        </div>
                        <div class="input-container">
                            <label for="confirm_password">Conferma Password:</label>
                            <input type="password" id="confirm_password" name="confirm_password" required
                                onkeyup="checkPassword()">
                        </div>
                        <p id="password_error" class="profile-error-message"></p>
                        <p id="passwordresponseMessage" class="profile-error-message"></p>
                        <div class="button-container">
                            <button type="submit" class="login-button" disabled style="background-color: #E63946;
                        color: #fff;">Cambia Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mp-footer">
        <?php include 'footer.php'; ?>
    </div>
</body>

</html>