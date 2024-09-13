<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userid = $_POST['id'];

    $query = "DELETE FROM user_data WHERE userid=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userid);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
$conn->close();
?>
