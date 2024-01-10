<?php

// $encryption = password($key,$txt);

// $decryption = password($key,$encrypted_txt, false);

function password(String $key, String $simple_string = null, $encrypt = true)
{
    // Storing the cipher method
    $ciphering = "AES-128-CTR";
// Using OpenSSl Encryption method
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;

// Non-NULL Initialization Vector for encryption
    $encryption_iv = '1234567891011121';

    if ($encrypt == true) {
        # Using openssl_encrypt() function to encrypt the data
        $decryption = openssl_encrypt($simple_string, $ciphering, $key, $options, $encryption_iv);
    } else {
        // Using openssl_decrypt() function to decrypt the data
        $decryption = openssl_decrypt($simple_string, $ciphering, $key, $options, $encryption_iv);
    }
    return $decryption;
}
