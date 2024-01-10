<?php
require '../../db.php';
require '../../mysql/index.php';

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $query = $postdata["query"];

    $result = query_result($query, $conn);

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
