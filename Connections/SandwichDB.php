<?php
$hostname = "localhost";
$database = "sandwich";
$username = "root";
$password = "root";

$mysqli = new mysqli($hostname, $username, $password , $database);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>