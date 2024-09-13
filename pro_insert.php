<?php

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

$nome = $conn->real_escape_string($_POST['nome']);
$cognome = $conn->real_escape_string($_POST['cognome']);
$email = $conn->real_escape_string($_POST['email']);
$indirizzo = $conn->real_escape_string($_POST['indirizzo']);
$password = $conn->real_escape_string($_POST['password']);
$professione = $conn->real_escape_string($_POST['professione']);
$piva = $conn->real_escape_string($_POST['piva']);
$p_orario = $conn->real_escape_string($_POST['p_orario']);
$p_chiamata = $conn->real_escape_string($_POST['p_chiamata']);
$rating = 0;

$sql = "SELECT * FROM homie.pro_data WHERE email = '$email' OR piva = '$piva'";
if ($conn->query($sql)->num_rows > 0) {
    header('Location:register_page_pro.php?error=Email o P.IVA già associata ad un account');
    exit();
}


$target_dir = "img/professionals/";

if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$imageFileType = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
$new_filename = strtolower($nome) . "-" . strtolower($cognome) . "-" . strtolower($piva) . "." . strtolower($imageFileType);
$target_file = $target_dir . $new_filename;
$uploadOk = 1;


$check = getimagesize($_FILES["photo"]["tmp_name"]);
if ($check !== false) {
    echo "File is an image - " . $check["mime"] . ".<br>";
    $uploadOk = 1;
} else {
    $uploadOk = 0;
    header('Location:register_page_pro.php?error=File is not an image.');
    exit();
}


if (file_exists($target_file)) {
    $uploadOk = 0;
    header('Location:register_page_pro.php?error=File already exists.');
    exit();
}


if ($_FILES["photo"]["size"] > 5000000) {
    $uploadOk = 0;
    header('Location:register_page_pro.php?error=File is too large.');
    exit();
}


if ($imageFileType != "jpeg") {
    $uploadOk = 0;
    header('Location:register_page_pro.php?error=Only JPG, JPEG, PNG & GIF files are allowed.');
    exit();
}


if ($uploadOk == 0) {
    header('Location:register_page_pro.php?error=Your file was not uploaded.');
    exit();
} else {
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["photo"]["name"])) . " has been uploaded.<br>";

        $sql = "INSERT INTO homie.pro_data (nome, cognome, email, indirizzo, password, professione, piva, prezzo_orario, prezzo_chiamata, rating, profile_picture)
                VALUES ('$nome', '$cognome', '$email', '$indirizzo', md5('$password'), '$professione', '$piva', '$p_orario', '$p_chiamata', '$rating', '$target_file')";

        if ($conn->query($sql) === TRUE) {
            // Set session variables
            $_SESSION['piva'] = $piva;
            $_SESSION['userid'] = $piva;
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $nome;
            $_SESSION['cognome'] = $cognome;
            $_SESSION['indirizzo'] = $indirizzo;
            $_SESSION['professione'] = $professione;
            $_SESSION['p_orario'] = $p_orario;
            $_SESSION['p_chiamata'] = $p_chiamata;
            $_SESSION['rating'] = $rating;
            $_SESSION['is_active'] = 0;

            header('Location:home_pro.php');
            exit();
        } else {
            header('Location:register_page_pro.php?error=Errore nella creazione dell\'account: ' . $conn->error);
            exit();
        }
    } else {
        header('Location:register_page_pro.php?error=Errore durante il caricamento del file.');
        exit();
    }
}

$conn->close();
?>
