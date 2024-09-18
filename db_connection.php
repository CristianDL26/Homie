<?php
$host = "db-mysql-ams3-28194-do-user-17642064-0.j.db.ondigitalocean.com";
$username = "doadmin";
$password = "AVNS_dTZI_5u1-UpzYDmcHO5";
$dbname = "homie";
$port=25060;

$conn = new mysqli($host, $username, $password, $dbname, $port);
$conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 30); 

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>


