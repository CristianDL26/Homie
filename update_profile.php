<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

$response = ['success' => false];



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $conn->real_escape_string($_POST['nome']);
    $cognome = $conn->real_escape_string($_POST['cognome']);
    $email = $conn->real_escape_string($_POST['email']);
    $indirizzo = $conn->real_escape_string($_POST['indirizzo']);
    $userid = $_SESSION['userid'];


    if (isset($_SESSION['professione'])) {
        $sql = "UPDATE homie.pro_data SET nome='$nome', cognome='$cognome', email='$email', indirizzo='$indirizzo' WHERE piva='$userid'";
    } else {
        $sql = "UPDATE homie.user_data SET nome='$nome', cognome='$cognome', email='$email', indirizzo='$indirizzo' WHERE userid='$userid'";
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['name'] = $nome;
        $_SESSION['cognome'] = $cognome;
        $_SESSION['email'] = $email;
        $_SESSION['indirizzo'] = $indirizzo;
        $response['success'] = true;

    } else {
        $response['message'] = 'Errore durante l\'aggiornamento: ' . $conn->error;
    }

    $conn->close();
}

echo json_encode($response);
?>