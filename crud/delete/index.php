echo json_encode($result);

<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../mysql/index.php';

$postdata = get_postdata();
if (isset($postdata['table'], $postdata['id']) && !empty($postdata['table']) && !empty($postdata['id'])) {
    $table = $postdata['table'];
    $id = $postdata['id'];
    $result = delete_by_id($table, $id);
    if ($result) {
        send_json(['msg' => 'ok', 'deleted' => true]);
    } else {
        send_json(['msg' => 'Delete failed', 'deleted' => false], 500);
    }
} else {
    send_json(['msg' => 'Missing table or id'], 400);
}
mysqli_close($conn);
