

<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../mysql/index.php';
require_once __DIR__ . '/functions.php';

$postdata = get_postdata();
$res = [];
if (isset($postdata['user'], $postdata['password'])) {
    $user = mysqli_real_escape_string($conn, $postdata['user']);
    $password = $postdata['password'];
    // Check if user already exists
    $sql = "SELECT * FROM `users` WHERE user = '$user'";
    $result = query_result($sql);
    if ($result[0] > 0) {
        send_json(['error' => 100, 'msg' => 'Account already exists'], 409);
    } else {
        // Hash password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertData = [
            'user' => $user,
            'password' => $hashedPassword
        ];
        $insertResult = insert('users', $insertData);
        if ($insertResult) {
            send_json(['error' => 0, 'msg' => 'Account created', 'res' => $insertResult], 201);
        } else {
            send_json(['error' => 500, 'msg' => 'Account creation failed'], 500);
        }
    }
} else {
    send_json(['error' => 400, 'msg' => 'Missing user or password'], 400);
}
mysqli_close($conn);
