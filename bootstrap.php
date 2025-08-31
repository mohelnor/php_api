<?php
// bootstrap.php: Centralizes DB connection, input parsing, and response helpers

require_once __DIR__ . '/header.php';

$servername = 'localhost';
$username   = 'root';
$dbname     = 'service_ordering_app';
$password   = '';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($conn, 'utf8');

if (!$conn) {
    send_json(['error' => 'Database connection failed: ' . mysqli_connect_error()], 500);
    exit;
}

// Get the posted data (JSON or form)
function get_postdata() {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        $data = $_POST;
    }
    return $data;
}

// Send JSON response with status code
function send_json($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
}
