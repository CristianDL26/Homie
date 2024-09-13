<?php
session_start();
require 'db_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        if (md5($password) == $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Password non corretta.";
        }
    } else {
        echo "Email non trovata.";
    }
    
    $stmt->close();
}
$conn->close();
?>
