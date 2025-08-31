

<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../mysql/index.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/token.php';
session_start();

$postdata = get_postdata();
$res = [];
if (isset($postdata['user'], $postdata['password'])) {
    $user = mysqli_real_escape_string($conn, $postdata['user']);
    $password = $postdata['password'];
    $sql = "SELECT * FROM `users` WHERE user = '$user'";
    $result = query_result($sql);
    if ($result[0] > 0) {
        $userData = $result[1][0];
        // Use password_verify if password is hashed, fallback to plain for legacy
        $valid = false;
        if (isset($userData['password'])) {
            if (password_verify($password, $userData['password']) || $userData['password'] === $password) {
                $valid = true;
            }
        }
        if ($valid) {
            $otp = otp();
            $userData['otp'] = $otp;
            // $_SESSION['user'] = $userData; // Session not needed with JWT
            // Generate JWT token
            $payload = [
                'user' => $userData['user'],
                'iat' => time(),
                'exp' => time() + 3600 // 1 hour expiry
            ];
            $secret = 'your_secret_key'; // TODO: move to config/env
            $token = generate_jwt($payload, $secret);
            send_json(['msg' => 200, 'token' => $token, 'res' => $userData]);
        } else {
            send_json(['msg' => 401, 'error' => 'Invalid password'], 401);
        }
    } else {
        send_json(['msg' => 404, 'error' => 'User not found'], 404);
    }
} else {
    send_json(['msg' => 400, 'error' => 'Missing user or password'], 400);
}
mysqli_close($conn);



// example of login with JWT
// {
//     "user": "user",
//     "password": "password"
// }
