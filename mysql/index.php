<?php

/// FOR COMPLEX QUERIES !!!!
// Query from given sql query ..
function query($sql, $conn)
{
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    return false;
}

// Query All Rows from sql query ..
function query_result($sql, $conn)
{
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $num_rows = mysqli_num_rows($result);
    return [$num_rows, $row];
}

// Fetch All Rows from given table_name ..
function fetch_all($table, $conn)
{
    $sql = "SELECT * FROM $table";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $row;
}

// Fetch All Rows from given table_name ..
function fetch_where($table, $where, $conn)
{
    $sql = "SELECT * FROM $table WHERE ";
    $sql .= $where;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $row;
}

// Fetch Row from given table_name & table_id ..
function fetch_by_id($table, $id, $conn)
{
    $sql = "SELECT * FROM $table WHERE `id` = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row;
}

// delete from table by id
function delete_by_id($table, $id, $conn)
{
    $sql = "DELETE FROM $table WHERE `id` = $id";
    if (mysqli_query($conn, $sql)) {
        return true;
    }

    return false;
}

// prepare Keys for insert
function get_keys(array $data, $method = "INSERT")
{
    $dataset = "";
    if ($method == "INSERT") {
        foreach ($data as $val) {
            $dataset .= "$val,";
        }
    } else {
        foreach ($data as $x => $val) {
            if (is_string($val)) {
                $dataset .= "$x = \"$val\", ";
            } else {
                $dataset .= "$x = $val, ";
            }
        }
    }
    return $dataset = rtrim($dataset, ', ');
}

// prepare date for insert
function get_values(array $data, $method = "INSERT")
{

    $dataset = "";
    if ($method == "INSERT") {
        foreach ($data as $val) {
            $dataset .= "\"$val\",";
        }
    } else {
        foreach ($data as $x => $val) {
            if (is_string($val)) {
                $dataset .= "$x = \"$val\", ";
            } else {
                $dataset .= "$x = $val, ";
            }
        }
    }
    return $dataset = rtrim($dataset, ', ');
}

// insert into Table with data ..
function insert($table, $data, $conn)
{

    $datekey = get_keys(array_keys($data));
    $datavalues = get_values(array_values($data));

    $sql = "INSERT INTO $table (";

    $sql .= $datekey;

    $sql .= ") VALUES (";

    $sql .= $datavalues;

    $sql .= ")";

    if (mysqli_query($conn, $sql)) {
        return true;
    }

    return false;
}

// insert("users", array("user"=>"35", "username"=>"37", "age"=>"43"));
// echo "\n";

// Multi insert into Table with data ..
function insertAll($table, $data, $conn)
{

    $query = '';

    foreach ($data as $val) {

        $datekey = get_keys(array_keys($val));
        $datavalues = get_values(array_values($val));

        $sql = "INSERT INTO $table (";
        $sql .= $datekey;
        $sql .= ") VALUES (";
        $sql .= $datavalues;
        $sql .= ");";

        $query .= $sql;
    }


    if (mysqli_multi_query($conn, $query)) {
        return true;
    }

    return [false, $query];
}

// insertAll("users", [["user"=>"35", "username"=>"37", "age"=>"43"],["user"=>"55", "username"=>"77", "age"=>"44"]],$conn);
// echo "\n";

// Update Table with data ..
function update($table, $id, array $data, $conn)
{

    $dataset = get_keys($data, "UPDATE");

    $sql = "UPDATE $table SET ";

    $sql .= $dataset;

    $sql .= "WHERE id = $id ";

    if (mysqli_query($conn, $sql)) {
        return true;
    }

    return false;
}

// update("users",1, array("user"=>"35", "username"=>"37", "age"=>"43"));

// Multi update Table with data ..
function updateAll($table, $data, $conn)
{

    $query = '';

    foreach ($data as $val) {

        $dataset = get_keys($val, "UPDATE");

        $sql = "UPDATE $table SET ";

        $sql .= $dataset;

        $id = $val['id'];
        $sql .= "WHERE id = $id ; ";

        $query .= $sql;
    }

    // print($query);
    if (mysqli_multi_query($conn, $query)) {
        return true;
    }

    return [false,$query];
}

// updateAll("users", [["id"=> 1,"user"=>"35", "username"=>"37", "age"=>"43"],["id"=>2,"user"=>"55", "username"=>"77", "age"=>"44"]],null);
// echo "\n";
