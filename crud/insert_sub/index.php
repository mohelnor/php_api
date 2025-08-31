

<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../mysql/index.php';
require_once __DIR__ . '/../../functions.php';

$postdata = get_postdata();
if (isset($postdata['table'], $postdata['data'], $postdata['table2'], $postdata['data2'], $postdata['key'])) {
    $table = $postdata['table'];
    $table2 = $postdata['table2'];
    $key = $postdata['key'];
    $data = $postdata['data'];
    $data2 = $postdata['data2'];
    $result = is_assoc($data) ? insertAll($table, $data, $conn) : insert($table, $data);
    if ($result) {
        $last_inserted_id = mysqli_insert_id($conn);
        foreach ($data2 as $k => $v) {
            $data2[$k][$key] = $last_inserted_id;
        }
        $result2 = is_assoc($data2) ? insertAll($table2, $data2, $conn) : insert($table2, $data2);
        send_json(['msg' => 'ok', 'insert_id' => $last_inserted_id, 'res' => $result2], 201);
    } else {
        send_json(['msg' => 'Insert failed'], 500);
    }
} else {
    send_json(['msg' => 'Missing required parameters'], 400);
}
mysqli_close($conn);
