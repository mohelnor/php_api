<?php
require '../../db.php';
require '../../mysql/index.php';
require '../../functions.php';

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $table = $postdata["table"];
    $data = $postdata["data"];
    $table2 = $postdata["table2"];
    $key = $postdata["key"];
    if (isset($postdata["id"])) {
        $id = $postdata["id"];
        $result = update($table, $id, $data, $conn);
    } else {
        $result = updateAll($table, $data, $conn);
    }

    $res['msg'] = 'Error , IN your syntac , check ur params';

    if ($result) {

        $res['msg'] = $result;

        if (isset($key)) {
            $data2 = $postdata["data2"];
            $result2 = updateAll($table2, $data2, $conn);
        }

        $res['msg'] = $result2;
    }
} else {
    $res['msg'] = 'Error , Didn\'t receive data ..';
}
echo json_encode($res);
mysqli_close($conn);
