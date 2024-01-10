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
        
        if (isset($postdata["keys"])) {
            $fin =[];
            $keys = $postdata["keys"];
            $subtable = $postdata["subtable"];
            $i = 0;
            foreach ($result as $value) {
                $where = $keys[0]." = " . $value[$keys[1]];
                // echo gettype($value), "\n";
                $nested = fetch_where($subtable, $where, $conn);
                $value += ["client_d" => $nested[1]];
                array_push($fin,$value); 
            }
            
            $result = $fin;
        }
        $res['res'] = $result;
    }
} else {
    $res['msg'] = 'Error , Didn\'t receive data ..';
}
echo json_encode($res);
mysqli_close($conn);
