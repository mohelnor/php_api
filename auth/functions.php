<?php
// Generate Password ...
function pwd($length = 8) {
    $pwd = substr(md5(rand(0, 32000)), 0, $length);
    return $pwd;
}

// Generate Password 2 ...
function pwdCaps($length = 8) {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

// Greater Password ...
function otp($length = 4) {
    $alphabet = '1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

// check if String is Email
function checkemail(String $email){
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

    if (!preg_match($email_exp, $email)) {
        return false;
    }
    return true;
}

//  check if it's name ..
function isName(String $name){    
    $string_exp = "/^[A-Za-z .'-]+$/";

    if (!preg_match($string_exp, $name)) {
        return false;
    }
    return true;
}

// create files names 
function namer($name){
    $date = date("Ymdhis");
    $name .= '_'.$date;
    return $name;
}

function is_assoc($data){
    if(array_keys($data) !== range(0, count($data) - 1)){
        $data = [$data];
    }
    return $data;
}

?>