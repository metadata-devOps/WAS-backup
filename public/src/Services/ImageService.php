<?php
namespace Services;

use Core\Auth;

class ImageService {
    public static function analyze(string $raw): array {
        if(!Auth::check()) throw new \RuntimeException("Auth fail");
        
        $tmp = tempnam(sys_get_temp_dir(), 'ph_');
        if ($tmp === false) throw new \RuntimeException("tmp fail");
        file_put_contents($tmp, $raw);

        $src = "php://filter/read=string.strip_tags/resource=" . $tmp;

        $meta = [];
        $info = @getimagesize($src, $meta);
        @unlink($tmp);

        $w = is_array($info) ? (int)($info[0] ?? 0) : 0;
        $h = is_array($info) ? (int)($info[1] ?? 0) : 0;
        $mime = is_array($info) ? (string)($info['mime'] ?? '') : '';

        return [
            'width' => $w,
            'height' => $h,
            'mime' => $mime,
            'debug_trace' => base64_encode(serialize($meta)),
        ];
    }
}
