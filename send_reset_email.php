<?php 
require 'vendor/autoload.php';
            
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
            
$mail = new PHPMailer(true);
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $sql = "SELECT userid FROM homie.user_data WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userid = $row['userid'];

        $token = bin2hex(random_bytes(50));
        $token_hash = hash('sha256', $token);
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $sql = "UPDATE homie.user_data SET reset_token_hash='$token_hash', reset_token_expires_at='$expiry' WHERE userid='$userid '";
        if ($conn->query($sql) === TRUE) {
            $resetLink = "https://homie.website/reset_password.php?token=$token_hash";
            
            try {
                
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'aiuto.homie@gmail.com'; 
                $mail->Password = 'aehv smop cfmm zjnb';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
            
                $mail->setFrom('aiuto.homie@gmail.com', 'Homie');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Recupero Password';
                $mail->Body    = "Clicca sul seguente link per resettare la tua password: <a href='$resetLink'>$resetLink</a>";
                $mail->AltBody = "Clicca sul seguente link per resettare la tua password: $resetLink";

                $mail->send();
                $response['success'] = true;
                $response['message'] = 'Email di recupero inviata con successo.';
            } catch (Exception $e) {
                $response['message'] = "L'email non può essere inviata. Errore di Mailer: {$mail->ErrorInfo}";
            }
        } else {
            $response['message'] = 'Errore durante il salvataggio del token: ' . $conn->error;
        }
    } else {
        $response['message'] = 'Email non trovata.';
    }

    $conn->close();
}

echo json_encode($response);
?>