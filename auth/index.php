<?php
require '../db.php';
require '../mysql/index.php';

session_start();
if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $request = json_decode($postdata, true);
    $name = mysqli_real_escape_string($conn, $request["name"]);
    $password = mysqli_real_escape_string($conn, $request["password"]);
    $query = "SELECT * FROM users WHERE name = '$name' AND password ='$password'";
    $result = query_result($query, $conn);

} else {
    $result['error'] = 'no data';
}

echo json_encode($result);
$_SESSION['user'] = $result[1][0];
mysqli_close($conn);
