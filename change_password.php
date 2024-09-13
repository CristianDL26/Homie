<?php
session_start();
include 'db_connection.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['userid'];
    $old_password = $conn->real_escape_string($_POST['old_password']);
    $new_password = $conn->real_escape_string($_POST['new_password']);

    if(isset($_SESSION['professione'])){
        $query = "SELECT password FROM homie.pro_data WHERE piva = ?";
    } else {
        $query = "SELECT password FROM homie.user_data WHERE userid = ?";
    }

    // Hash the old password using MD5
    $old_password_md5 = md5($old_password);

    // Fetch the current password from the database
    //$query = "SELECT password FROM homie.user_data WHERE userid = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        $response['message'] = 'Errore nella preparazione della query: ' . $conn->error;
        echo json_encode($response);
        exit();
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $response['message'] = 'Utente non trovato.';
        echo json_encode($response);
        exit();
    }
    $row = $result->fetch_assoc();
    $password = $row['password'];
        // Debug log
        error_log("Old password (user input): $old_password_md5");
        error_log("Password from database: $password");

    // Verify the old password
    if ($old_password_md5 != $password) {
        $response['message'] = "La vecchia password non è corretta.";
        echo json_encode($response);
        exit();
    }

    // Hash the new password using MD5
    $new_password_md5 = md5($new_password);

    // Update the password in the database
    $query = "UPDATE homie.user_data SET password = ? WHERE userid = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        $response['message'] = 'Errore nella preparazione della query: ' . $conn->error;
        echo json_encode($response);
        exit();
    }
    $stmt->bind_param("si", $new_password_md5, $user_id);

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['message'] = 'Errore durante l\'aggiornamento: ' . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

echo json_encode($response);
?>