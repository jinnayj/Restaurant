<?php
// Database connection using PDO
$host = "localhost";
$user = "root";
$pass = "";
$db   = "trs_db";

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);}

?>