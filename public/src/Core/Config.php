<?php
namespace Core;

use Utils\MemoryUtil;

class Config {
    public static function getSessionKey(): string {
        $k = @file_get_contents('/tmp/app.key');
        return $k;
    }

    public static function getFlag(): string {
        $f = @file_get_contents('/tmp/flag.txt');
        return $f;
    }
}
