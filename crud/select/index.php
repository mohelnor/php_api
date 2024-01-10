<?php
require '../../db.php';
require '../../mysql/index.php';

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $table = $postdata["table"];

    if (isset($postdata["id"])) {
        $id = $postdata["id"];
        $result = fetch_by_id($table, $id, $conn);
    } elseif (isset($postdata["where"])) {
        $where = $postdata["where"];
        $result = fetch_where($table, $where, $conn);
    } else {
        $result = fetch_all($table, $conn);
    }

    $res['msg'] = 'Error , IN your syntac , check ur params';

    if ($result) {
        $res['msg'] = "ok";
        $res['res'] = $result;
    }
    
} else {
    $res['msg'] = 'Error , Didn\'t receive data ..';
}
echo json_encode($res);
mysqli_close($conn);
