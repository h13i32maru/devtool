<?php
/**
 * ランダムな文字列を生成する
 */
function randomString($size = 40)
{
    if ($size > 40) {
        throw new InvalidArgumentException();
    }

    $seed1 = (int)mt_rand(100000000, 999999999);
    $seed2 = Time::now();

    $str = "${seed1}:${seed2}";

    return substr(sha1($str), 0, $size);
}
