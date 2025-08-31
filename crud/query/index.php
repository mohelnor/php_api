

<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../mysql/index.php';

$postdata = get_postdata();
if (isset($postdata['query']) && !empty($postdata['query'])) {
    $query = $postdata['query'];
    $result = query_result($query);
    if ($result) {
        send_json(['msg' => 'ok', 'res' => $result]);
    } else {
        send_json(['msg' => 'Query failed'], 500);
    }
} else {
    send_json(['msg' => 'Missing query'], 400);
}
mysqli_close($conn);
