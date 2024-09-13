<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $piva = $_POST['id'];

    $query = "DELETE FROM pro_data WHERE piva=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $piva);

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
