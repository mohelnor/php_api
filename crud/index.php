<?php
require '../db.php';
require '../mysql/index.php';
if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $table = $postdata["table"];
    $row = fetch_all($table, $conn);

}
// echo json_encode($postdata);
echo json_encode($row);
mysqli_close($conn);
