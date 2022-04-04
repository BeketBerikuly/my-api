<?php

namespace App\Helpers;

class Phones
{
    public static function normalize(string $phone)
    {
        return str_replace([' ', '.', '-', '(', ')', '+'], '', $phone);
    }

    public static function isDigits(string $phone)
    {
        return preg_match('/^[0-9]/', $phone);
    }
}
