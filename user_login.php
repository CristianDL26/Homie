<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'db_connection.php';

$email = $conn->real_escape_string($_POST['email']);
$password = $conn->real_escape_string($_POST['password']);
$password_md5 = md5($password);

error_log("Email: $email");
error_log("MD5 Hashed Password: $password_md5");

$query = "SELECT * FROM homie.user_data WHERE email = ? AND password = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    error_log("Query error: " . $conn->error);
    header('Location: login_page.php?error=Errore nella query al database');
    exit();
}

$stmt->bind_param("ss", $email, $password_md5);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    error_log("Query error: " . $conn->error);
    header('Location: login_page.php?error=Errore nella query al database');
    exit();
} else {
    error_log("Number of rows returned: " . $result->num_rows);
}

if ($result->num_rows == 0) {
    error_log("Credenziali non valide per l'utente con email: $email");
    header('Location: login_page.php?error=Credenziali non valide');
    exit();
} else {
    $row = $result->fetch_assoc();
    $userid = $row['userid'];
    $_SESSION['userid'] = $userid;
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $row['nome'];
    $_SESSION['cognome'] = $row['cognome'];
    $_SESSION['indirizzo'] = $row['indirizzo'];

    $admin_query = "SELECT * FROM homie.admin WHERE id = ?";
    $admin_stmt = $conn->prepare($admin_query);

    if (!$admin_stmt) {
        error_log("Query error: " . $conn->error);
        header('Location: login_page.php?error=Errore nella query al database');
        exit();
    }

    $admin_stmt->bind_param("i", $userid);
    $admin_stmt->execute();
    $admin_result = $admin_stmt->get_result();

    if ($admin_result->num_rows > 0) {
        $_SESSION['is_admin'] = true;
    } else {
        $_SESSION['is_admin'] = false;
    }

    $admin_stmt->close();

    header('Location: home.php');
    exit();
}

$stmt->close();
$conn->close();
?>
