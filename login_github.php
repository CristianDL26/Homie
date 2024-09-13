<?php

$clientID = '1088072986305-15q7hivcujlps3spoou4ntcnf9jbmofu.apps.googleusercontent.com';
$redirectUri = 'http://localhost/homie/callback.php';
$authUrl = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
    'client_id' => $clientID,
    'redirect_uri' => $redirectUri,
    'response_type' => 'code',
    'scope' => 'email profile', 
    'access_type' => 'offline'
]);

header('Location: ' . $authUrl);
exit();

?>
