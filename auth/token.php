<?php
// Simple JWT implementation for PHP (no external dependencies)
function generate_jwt($payload, $secret, $algo = 'HS256') {
    $header = json_encode(['typ' => 'JWT', 'alg' => $algo]);
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}

function verify_jwt($jwt, $secret) {
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return false;
    list($header, $payload, $signature) = $parts;
    $valid_signature = str_replace(['+', '/', '='], ['-', '_', ''],
        base64_encode(hash_hmac('sha256', "$header.$payload", $secret, true))
    );
    if (!hash_equals($valid_signature, $signature)) return false;
    $payload = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
    if (isset($payload['exp']) && $payload['exp'] < time()) return false;
    return $payload;
}
