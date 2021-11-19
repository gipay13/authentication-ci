<?php

function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min;
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1;
    $bits = (int) $log + 1;
    $filter = (int) (1 << $bits) - 1;
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter;
    } while ($rnd > $range);
    return $min + $rnd;
}

function generate_token($length)
{
    $token = "";
    $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $alphabet.= "abcdefghijklmnopqrstuvwxyz";
    $alphabet.= "0123456789";
    $max = strlen($alphabet);

    for ($i=0; $i < $length; $i++) {
        $token .= $alphabet[crypto_rand_secure(0, $max-1)];
    }

    return $token;
}