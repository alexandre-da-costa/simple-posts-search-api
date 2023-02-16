<?php

namespace App\Helpers;

class Token
{
    public static function generate(): string
    {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
