<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}include 'db_connection.php';


$email = $conn->real_escape_string($_POST['email']);
$password = $conn->real_escape_string($_POST['password']);
$password_md5 = md5($password);


$query = "SELECT * FROM homie.pro_data WHERE email = ? AND password = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    error_log("Query error: " . $conn->error);
    header('Location: login_page.php?error=Errore nella query al database');
    exit();
}

$stmt->bind_param("ss", $email, $password_md5);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location:login_page_pro.php?error= Credenziali non valide');
} 
else {
    $row = $result->fetch_assoc(); 
    
    $_SESSION['email'] = $email;
    $_SESSION['piva'] = $row['piva'];
    $_SESSION['userid'] = $row['piva'];
    $_SESSION['name'] = $row['nome'];
    $_SESSION['cognome'] = $row['cognome'];
    $_SESSION['indirizzo'] = $row['indirizzo'];
    $_SESSION['professione'] = $row['professione'];
    $_SESSION['p_orario'] = $row['prezzo_orario'];
    $_SESSION['p_chiamata'] = $row['prezzo_chiamata'];
    $_SESSION['is_active'] = $row['is_active'];
    $_SESSION['rating'] = $row['rating'];
    header('Location:home_pro.php');
    exit();
}

$conn->close();
?>