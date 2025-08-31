

<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../mysql/index.php';

$postdata = get_postdata();
if (isset($postdata['table']) && !empty($postdata['table'])) {
    $table = $postdata['table'];
    if (isset($postdata['id'])) {
        $id = $postdata['id'];
        $result = fetch_by_id($table, $id);
    } elseif (isset($postdata['where'])) {
        $where = $postdata['where'];
        $result = fetch_where($table, $where);
    } else {
        $result = fetch_all($table);
    }
    // Handle subtable join if keys provided
    if (isset($postdata['keys'], $postdata['subtable'])) {
        $fin = [];
        $keys = $postdata['keys'];
        $subtable = $postdata['subtable'];
        foreach ($result as $value) {
            $where = $keys[0] . ' = ' . $value[$keys[1]];
            $nested = fetch_where($subtable, $where);
            $value['client_d'] = $nested[1] ?? $nested;
            $fin[] = $value;
        }
        $result = $fin;
    }
    send_json(['msg' => 'ok', 'res' => $result]);
} else {
    send_json(['msg' => 'Missing table'], 400);
}
mysqli_close($conn);
