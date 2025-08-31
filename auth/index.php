<!-- echo json_encode($result); -->

<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../mysql/index.php';
session_start();

require_once __DIR__ . '/token.php';

// Check for Bearer token
$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : (isset($headers['authorization']) ? $headers['authorization'] : null);
$secret = 'your_secret_key'; // TODO: move to config/env
if (!$authHeader || !preg_match('/Bearer\s(.*)/', $authHeader, $matches)) {
    send_json(['error' => 'Missing or invalid token'], 401);
    exit;
}
$jwt = $matches[1];
$payload = verify_jwt($jwt, $secret);
if (!$payload) {
    send_json(['error' => 'Invalid or expired token'], 401);
    exit;
}

$postdata = get_postdata();
if (isset($postdata['name'], $postdata['password'])) {
    $name = mysqli_real_escape_string($conn, $postdata['name']);
    $password = mysqli_real_escape_string($conn, $postdata['password']);
    $query = "SELECT * FROM users WHERE name = '$name' AND password ='$password'";
    $result = query_result($query);
    if ($result[0] > 0) {
        $_SESSION['user'] = $result[1][0];
        send_json($result[1][0]);
    } else {
        send_json(['error' => 'Invalid credentials'], 401);
    }
} else {
    send_json(['error' => 'Missing name or password'], 400);
}
send_json(['msg' => 'Authenticated', 'user' => $payload['user']]);
mysqli_close($conn);
