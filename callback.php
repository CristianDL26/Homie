<?php
include 'db_connection.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$clientID = '1088072986305-15q7hivcujlps3spoou4ntcnf9jbmofu.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'https://homie.website/callback.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

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

    $data = json_decode($response, true);
    $accessToken = $data['access_token'];

    $userInfoUrl = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=" . $accessToken;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $userInfo = curl_exec($ch);
    curl_close($ch);

    $userData = json_decode($userInfo, true);
    $googleId = $userData['id'];
    $email = $userData['email'];
    $name = $userData['given_name'];
    $surname = $userData['family_name'];
    $_SESSION['name'] = $name;
    $_SESSION['cognome'] = $surname;
    $_SESSION['email'] = $email;

    echo $email;


    $query = "SELECT * FROM user_data WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['userid'];
        $_SESSION['userid'] = $userId;
        $_SESSION['indirizzo'] = $user['indirizzo'];


        $query2 = "SELECT * FROM admin WHERE id = ?";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param("s", $userId);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        if ($result2->num_rows > 0) {
            $user = $result2->fetch_assoc();
            $userId = $user['userid'];
            $email = $user['email'];
            $_SESSION['admin_id'] = $userId;
            $_SESSION['admin_email'] = $email;
            $_SESSION['is_admin'] = true;
            header('Location: home.php');
            exit();
        } else {
            header('Location: home.php');
            exit();
        }
    } else {
        header('Location: register_page.php');
        exit();
    }
} else {
    header('Location: login_page.php');
}
?>