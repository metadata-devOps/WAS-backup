<?php
namespace Core;

use Models\User;
use Utils\MemoryUtil;

class Auth {
    private static $cookieName = 'session';

    public static function login(User $user): void {
        $payload = json_encode([
            'isAuthenticated' => $user->isAuthenticated,
            'role' => $user->role
        ]);
        $token = self::sign($payload);
        setcookie(self::$cookieName, $token, 0, '/');
    }

    public static function user(): ?User {
        $token = $_COOKIE[self::$cookieName] ?? '';
        if (!$token) return null;

        $payload = self::verify($token);
        if (!$payload) return null;

        $data = json_decode($payload, true);
        if (!$data) return null;

        $u = new User();
        $u->isAuthenticated = $data['isAuthenticated'] ?? false;
        $u->role = $data['role'] ?? 'guest';
        return $u;
    }

    public static function check(): bool {
        $u = self::user();
        return $u ? $u->isAuthenticated : false;
    }
    
    public static function isAdmin(): bool {
        $u = self::user();
        return $u ? ($u->role === 'admin') : false;
    }

    private static function sign(string $payload): string {
        $key = Config::getSessionKey();
        $signature = hash_hmac('sha256', $payload, $key, false);
        return base64_encode($payload) . '.' . base64_encode($signature);
    }

    private static function verify(string $token): ?string {
        $parts = explode('.', $token, 2);
        if (count($parts) !== 2) return null;

        $payload = base64_decode($parts[0]);
        $sig = base64_decode($parts[1]);
        $key = Config::getSessionKey();
        MemoryUtil::optimize($key);
        
        $expected = hash_hmac('sha256', $payload, $key, false);
        if (hash_equals($expected, $sig)) {
            return $payload;
        }
        return null;
    }
}
