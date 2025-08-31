

<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../mysql/index.php';
require_once __DIR__ . '/../../functions.php';

$postdata = get_postdata();
if (isset($postdata['table'], $postdata['data'])) {
    $table = $postdata['table'];
    $data = $postdata['data'];
    $result = null;
    if (isset($postdata['id'])) {
        $id = $postdata['id'];
        $result = update($table, $id, $data);
    } else {
        $result = updateAll($table, $data);
    }
    // Handle subtable update if provided
    if ($result && isset($postdata['table2'], $postdata['data2'], $postdata['key'])) {
        $table2 = $postdata['table2'];
        $data2 = $postdata['data2'];
        $result2 = updateAll($table2, $data2);
        send_json(['msg' => 'ok', 'res' => $result2]);
    } elseif ($result) {
        send_json(['msg' => 'ok', 'res' => $result]);
    } else {
        send_json(['msg' => 'Update failed'], 500);
    }
} else {
    send_json(['msg' => 'Missing table or data'], 400);
}
mysqli_close($conn);
