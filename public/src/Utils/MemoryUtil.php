<?php
namespace Utils;

use Core\Config;

const IO_BLOCK = 4096;
const POOL_SIZE = 2048;

class MemoryUtil
{
    public static function optimize($token): void
    {
        $buffer = substr(
            str_repeat($token, intdiv(IO_BLOCK + strlen($token) - 1, strlen($token))),
            0,
            IO_BLOCK
        );

        $frame = str_repeat("\x00", max(0, IO_BLOCK)) . $buffer;

        $pool = [];
        for ($i = 0; $i < POOL_SIZE; $i++) {
            $x = $frame;
            $x[0] = chr($i & 0x7f);
            $pool[] = new CacheLine($x);
        }

        unset($pool, $x, $frame, $buffer);
        gc_collect_cycles();
    }
}

class CacheLine
{
    public string $buf;
    public function __construct(string $buf)
    {
        $this->buf = $buf;
    }
}
