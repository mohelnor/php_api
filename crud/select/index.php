

<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../mysql/index.php';

$postdata = get_postdata();
if (isset($postdata['table']) && !empty($postdata['table'])) {
    $table = $postdata['table'];
    if (isset($postdata['id'])) {
        $id = $postdata['id'];
        $result = fetch_by_id($table, $id);
        send_json(['msg' => 'ok', 'res' => $result]);
    } elseif (isset($postdata['where'])) {
        $where = $postdata['where'];
        $result = fetch_where($table, $where);
        send_json(['msg' => 'ok', 'res' => $result]);
    } else {
        $result = fetch_all($table);
        send_json(['msg' => 'ok', 'res' => $result]);
    }
} else {
    send_json(['msg' => 'Missing table'], 400);
}
mysqli_close($conn);
