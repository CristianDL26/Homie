
<?php
include 'db_connection.php';
session_start();

// Parametri di configurazione di Google OAuth 2.0
$clientID = '1088072986305-15q7hivcujlps3spoou4ntcnf9jbmofu.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/homie/callback.php'; // Deve corrispondere a quello registrato su Google Developer Console

// Verifica se c'è un codice di autorizzazione nella query string
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Scambia il codice di autorizzazione con un token di accesso
    $tokenUrl = "https://oauth2.googleapis.com/token";
    $postFields = [
        'code' => $code,
        'client_id' => $clientID,
        'client_secret' => $clientSecret,
        'redirect_uri' => $redirectUri,
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decodifica la risposta e ottieni il token di accesso
    $data = json_decode($response, true);
    $accessToken = $data['access_token'];

    // Usa il token di accesso per ottenere i dati dell'utente
    $userInfoUrl = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=" . $accessToken;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $userInfo = curl_exec($ch);
    curl_close($ch);

    // Decodifica i dati dell'utente
    $userData = json_decode($userInfo, true);
    $googleId = $userData['id'];
    $email = $userData['email'];
    $name = $userData['given_name'];
    $surname=$userData['family_name'];
    $_SESSION['name']=$name;
    $_SESSION['cognome']=$surname;

    //echo '<pre>' . print_r($userData, true) . '</pre>';

    // Passo 1: Verifica se esiste già un utente con questo GitHub ID
    $query = "SELECT * FROM user_data WHERE github_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $googleId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Utente già registrato con Google
        $user = $result->fetch_assoc();
        $userId=$user['userid'];

        $query = "SELECT * FROM admin WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Utente già registrato con Google è admin
            $user = $result->fetch_assoc();
            $userId=$user['userid'];
            $_SESSION['admin_id']=$userId;
            $_SESSION['admin_email'] = $email;
            header('Location: home.php');
            exit();
        } else{
        $_SESSION['userid'] = $userId; // Salva l'ID utente nella sessione
        header('Location: home.php');
        exit();
        }
    } else {
        // Passo 2: Se non esiste un utente con questo GitHub ID, verifica se esiste un account con la stessa email
        $query = "SELECT * FROM user_data WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Utente già registrato con la stessa email
            $user = $result->fetch_assoc();
            $userId=$user['userid'];
            $_SESSION['userid']=$userId;

            $query2 = "SELECT * FROM admin WHERE id = ?";
            $stmt2 = $conn->prepare($query2);
            $stmt2->bind_param("s", $userId);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if ($result2->num_rows > 0) {
                // Utente già registrato con Google è admin
                $user = $result2->fetch_assoc();
                $userId=$user['userid'];
                $email=$user['email'];
                $_SESSION['admin_id']=$userId;
                $_SESSION['admin_email'] = $email;
                $_SESSION['is_admin']=true;
                header('Location: home.php');
                exit();
            } else {

            /*$updateQuery = "UPDATE user_data SET github_id = ? WHERE email = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ss", $googleId, $email);
            $stmt->execute();*/
                header('Location: home.php'); // Reindirizza alla dashboard
                exit();
            }
        } else {
            // Passo 3: Se non esiste un account con la stessa email torna alla registrazione

            header('Location: register_page.php'); 
            exit();
        }
    }
} else {
    echo 'Codice di autorizzazione non fornito.';
}
?>

