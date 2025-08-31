<?php
require_once __DIR__ . '/../bootstrap.php';

/**
 * Execute a generic SQL query (use only for trusted queries).
 * @param string $sql
 * @return bool|string True on success, error message on failure
 */
function query($sql)
{
    global $conn;
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    return mysqli_error($conn);
}

/**
 * Query all rows from SQL query.
 * @param string $sql
 * @return array [num_rows, rows] or error message
 */
function query_result($sql)
{
    global $conn;
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        return mysqli_error($conn);
    }
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $num_rows = mysqli_num_rows($result);
    return [$num_rows, $row];
}

/**
 * Fetch all rows from a table (safe, but table name must be trusted).
 * @param string $table
 * @return array|false
 */
function fetch_all($table)
{
    global $conn;
    $table = mysqli_real_escape_string($conn, $table);
    $sql = "SELECT * FROM `$table`";
    $result = mysqli_query($conn, $sql);
    if (!$result) return false;
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $row;
}

/**
 * Fetch all rows from a table with a WHERE clause (where must be trusted).
 * @param string $table
 * @param string $where
 * @return array|false
 */
function fetch_where($table, $where)
{
    global $conn;
    $table = mysqli_real_escape_string($conn, $table);
    $sql = "SELECT * FROM `$table` WHERE $where";
    $result = mysqli_query($conn, $sql);
    if (!$result) return false;
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $row;
}

/**
 * Fetch a row by ID (safe, uses prepared statement).
 * @param string $table
 * @param int $id
 * @return array|null|false
 */
function fetch_by_id($table, $id)
{
    global $conn;
    $table = mysqli_real_escape_string($conn, $table);
    $id = (int)$id;
    $sql = "SELECT * FROM `$table` WHERE `id` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row;
}

/**
 * Delete a row by ID (safe, uses prepared statement).
 * @param string $table
 * @param int $id
 * @return bool|string
 */
function delete_by_id($table, $id)
{
    global $conn;
    $table = mysqli_real_escape_string($conn, $table);
    $id = (int)$id;
    $sql = "DELETE FROM `$table` WHERE `id` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return mysqli_error($conn);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $success = mysqli_stmt_execute($stmt);
    $err = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    return $success ? true : $err;
}
function get_keys(array $data, $method = "INSERT")
{
    if ($method == "INSERT") {
        return implode(",", array_map(function($val) { return "`$val`"; }, $data));
    } else {
        $pairs = [];
        foreach ($data as $x => $val) {
            $pairs[] = is_string($val) ? "`$x` = '" . addslashes($val) . "'" : "`$x` = $val";
        }
        return implode(", ", $pairs);
    }
}

// Helper: prepare values for insert
function get_values(array $data, $method = "INSERT")
{
    if ($method == "INSERT") {
        return implode(",", array_map(function($val) {
            return is_string($val) ? "'" . addslashes($val) . "'" : $val;
        }, $data));
    } else {
        $pairs = [];
        foreach ($data as $x => $val) {
            $pairs[] = is_string($val) ? "`$x` = '" . addslashes($val) . "'" : "`$x` = $val";
        }
        return implode(", ", $pairs);
    }
}

/**
 * Insert a row into a table (safe for values, not for table/column names).
 * @param string $table
 * @param array $data
 * @return bool|string
 */
function insert($table, $data)
{
    global $conn;
    $table = mysqli_real_escape_string($conn, $table);
    $columns = array_keys($data);
    $placeholders = implode(",", array_fill(0, count($columns), '?'));
    $types = str_repeat('s', count($columns));
    $sql = "INSERT INTO `$table` (" . implode(",", array_map(function($col) { return "`$col`"; }, $columns)) . ") VALUES ($placeholders)";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return mysqli_error($conn);
    mysqli_stmt_bind_param($stmt, $types, ...array_values($data));
    $success = mysqli_stmt_execute($stmt);
    $err = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    return $success ? true : $err;
}

/**
 * Insert multiple rows into a table (safe for values, not for table/column names).
 * @param string $table
 * @param array $data
 * @return bool|string
 */
function insertAll($table, $data)
{
    global $conn;
    $table = mysqli_real_escape_string($conn, $table);
    if (empty($data)) return false;
    $columns = array_keys($data[0]);
    $colSql = implode(",", array_map(function($col) { return "`$col`"; }, $columns));
    $placeholders = '(' . implode(',', array_fill(0, count($columns), '?')) . ')';
    $allPlaceholders = implode(',', array_fill(0, count($data), $placeholders));
    $sql = "INSERT INTO `$table` ($colSql) VALUES $allPlaceholders";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return mysqli_error($conn);
    $flat = [];
    foreach ($data as $row) {
        foreach ($columns as $col) {
            $flat[] = $row[$col];
        }
    }
    $types = str_repeat('s', count($flat));
    mysqli_stmt_bind_param($stmt, $types, ...$flat);
    $success = mysqli_stmt_execute($stmt);
    $err = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    return $success ? true : $err;
}

/**
 * Update a row by ID (safe for values, not for table/column names).
 * @param string $table
 * @param int $id
 * @param array $data
 * @return bool|string
 */
function update($table, $id, array $data)
{
    global $conn;
    $table = mysqli_real_escape_string($conn, $table);
    $id = (int)$id;
    $columns = array_keys($data);
    $setSql = implode(',', array_map(function($col) { return "`$col` = ?"; }, $columns));
    $sql = "UPDATE `$table` SET $setSql WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return mysqli_error($conn);
    $types = str_repeat('s', count($columns)) . 'i';
    $params = array_merge(array_values($data), [$id]);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    $success = mysqli_stmt_execute($stmt);
    $err = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    return $success ? true : $err;
}

/**
 * Update multiple rows by ID (safe for values, not for table/column names).
 * @param string $table
 * @param array $data
 * @return bool|string
 */
function updateAll($table, $data)
{
    global $conn;
    $table = mysqli_real_escape_string($conn, $table);
    if (empty($data)) return false;
    $columns = array_keys($data[0]);
    $success = true;
    foreach ($data as $row) {
        $id = (int)$row['id'];
        $updateData = $row;
        unset($updateData['id']);
        $setSql = implode(',', array_map(function($col) { return "`$col` = ?"; }, array_keys($updateData)));
        $sql = "UPDATE `$table` SET $setSql WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) return mysqli_error($conn);
        $types = str_repeat('s', count($updateData)) . 'i';
        $params = array_merge(array_values($updateData), [$id]);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        $res = mysqli_stmt_execute($stmt);
        $err = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        if (!$res) {
            $success = $err;
            break;
        }
    }
    return $success;
}

// updateAll("users", [["id"=> 1,"user"=>"35", "username"=>"37", "age"=>"43"],["id"=>2,"user"=>"55", "username"=>"77", "age"=>"44"]],null);
// echo "\n";
