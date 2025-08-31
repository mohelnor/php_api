
<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../mysql/index.php';

$postdata = get_postdata();
if (isset($postdata['table']) && !empty($postdata['table'])) {
    $table = $postdata['table'];
    $row = fetch_all($table);
    send_json($row);
} else {
    send_json(['error' => 'No table specified'], 400);
}
mysqli_close($conn);
