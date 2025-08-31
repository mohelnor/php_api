
<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../mysql/index.php';
require_once __DIR__ . '/../../functions.php';

$postdata = get_postdata();
if (isset($postdata['table'], $postdata['data']) && !empty($postdata['table']) && !empty($postdata['data'])) {
    $table = $postdata['table'];
    $data = $postdata['data'];
    // Insert data (single or multiple rows)
    $result = is_assoc($data) ? insertAll($table, $data, $conn) : insert($table, $data);
    if ($result) {
        $last_inserted_id = mysqli_insert_id($conn);
        send_json(['msg' => 'ok', 'insert_id' => $last_inserted_id, 'res' => $result], 201);
    } else {
        send_json(['msg' => 'Insert failed'], 500);
    }
} else {
    send_json(['msg' => 'Missing table or data'], 400);
}
mysqli_close($conn);
