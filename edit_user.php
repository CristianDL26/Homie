<?php
include 'db_connection.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_POST['userid'];
    $is_admin = $_POST['is_admin'];

    $query = "UPDATE user_data SET nome = ?, cognome = ?, email = ?, indirizzo = ? WHERE userid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $_POST['nome'], $_POST['cognome'], $_POST['email'], $_POST['indirizzo'], $userid);
    $stmt->execute();

    if ($stmt->affected_rows >= 0) {
        if ($is_admin == 1) {
            $admin_check_query = "SELECT * FROM admin WHERE id = ?";
            $admin_check_stmt = $conn->prepare($admin_check_query);
            $admin_check_stmt->bind_param("i", $userid);
            $admin_check_stmt->execute();
            $admin_result = $admin_check_stmt->get_result();

            if ($admin_result->num_rows == 0) {
                $admin_insert_query = "INSERT INTO admin (id, email, password) SELECT userid, email, password FROM user_data WHERE userid = ?";
                $admin_insert_stmt = $conn->prepare($admin_insert_query);
                $admin_insert_stmt->bind_param("i", $userid);
                $admin_insert_stmt->execute();
            }
        } else {
            $admin_delete_query = "DELETE FROM admin WHERE id = ?";
            $admin_delete_stmt = $conn->prepare($admin_delete_query);
            $admin_delete_stmt->bind_param("i", $userid);
            $admin_delete_stmt->execute();
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento dei dati utente.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Richiesta non valida.']);
}
?>