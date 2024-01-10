<?php
//this will show error if any error happens
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

//enable cors
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-type: text/html; charset=UTF-8');
header("Content-Type: application/json");
