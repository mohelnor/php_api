<?php
require 'header.php';

$servername = "localhost";
$username   = "root";
$dbname     = "manarah";
$password   = "root1234";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the posted data.
$postdata = json_decode(file_get_contents("php://input"), true);

if (empty($postdata)) {
    $postdata = $_POST;
}
