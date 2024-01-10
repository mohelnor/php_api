<?php
date_default_timezone_set('Africa/Khartoum');
ini_set('date.timezone', 'Africa/Khartoum');

$date = new DateTime();
$curTime = $date->getTimestamp();

// echo "\n";
$date = date("Ymdhis");
$random = (string) rand(01, 39);
$code = $date . $random;
