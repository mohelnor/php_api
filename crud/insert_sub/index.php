<?php
require '../../db.php';
require '../../mysql/index.php';
require '../../functions.php';

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $table = $postdata["table"];
    $table2 = $postdata["table2"];
    $key = $postdata["key"];
    $data = is_assoc($postdata["data"]);
    $result = insertAll($table, $data, $conn);

    $res['msg'] = 'Error , IN your syntac , check ur params';

    if ($result) {

        if (isset($key)) {
            $last_inserted_id = mysqli_insert_id($conn);
            $data2 = is_assoc($postdata["data2"]);
            foreach ($data2 as $k => $v) {
                #adding $last_inserted_id to $data2
                $data2[$k][$key] = $last_inserted_id;
            }
            $result2 = insertAll($table2, $data2, $conn);
        }

        $res['msg'] = $result2;
    }
} else {
    $res['msg'] = 'Error , Didn\'t receive data ..';
}
echo json_encode($res);
mysqli_close($conn);
