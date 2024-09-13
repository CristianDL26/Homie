<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['piva'])) {
    $piva = $_POST['piva'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $indirizzo = $_POST['indirizzo'];
    $professione = $_POST['professione'];
    $prezzo_orario = $_POST['prezzo_orario'];
    $prezzo_chiamata = $_POST['prezzo_chiamata'];
    $is_active = $_POST['is_active'];
    $rating = $_POST['rating'];

    $query = "UPDATE pro_data SET nome = ?, cognome = ?, email = ?, indirizzo = ?, professione = ?, prezzo_orario = ?, prezzo_chiamata = ?, rating = ?, is_active = ? WHERE piva = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssddis", $nome, $cognome, $email, $indirizzo, $professione, $prezzo_orario, $prezzo_chiamata, $rating, $is_active, $piva);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Richiesta non valida.']);
}
$conn->close();
?>
