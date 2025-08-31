

<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../mysql/index.php';
require_once __DIR__ . '/../../functions.php';

$postdata = get_postdata();
if (isset($postdata['table'], $postdata['data']) && !empty($postdata['table']) && !empty($postdata['data'])) {
    $table = $postdata['table'];
    $data = $postdata['data'];
    if (isset($postdata['id'])) {
        $id = $postdata['id'];
        $result = update($table, $id, $data);
    } else {
        $result = updateAll($table, $data);
    }
    if ($result) {
        send_json(['msg' => 'ok', 'res' => $result]);
    } else {
        send_json(['msg' => 'Update failed'], 500);
    }
} else {
    send_json(['msg' => 'Missing table or data'], 400);
}
mysqli_close($conn);
