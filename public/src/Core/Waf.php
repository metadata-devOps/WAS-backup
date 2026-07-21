<?php
namespace Core;

use Core\Database;

class Waf {
    private $bannedPatterns = [];
    private $db;

    private $dangerousKeywords = [
        'ATTACH', 'DETACH', 'PRAGMA', 'VACUUM', 'ANALYZE', 'EXPLAIN', 'QUERY PLAN'
    ];

    public function __construct() {
        $this->db = new Database();
        $this->buildBlockList();
    }

    private function buildBlockList() {
        foreach ($this->dangerousKeywords as $keyword) {
            $this->bannedPatterns[strtolower($keyword)] = '/\b' . preg_quote($keyword, '/') . '\b/i';
        }
        
        $this->bannedPatterns['semicolon'] = '/;/';
    }

    public function check(string $input) {
        foreach ($this->bannedPatterns as $name => $pattern) {
            if (preg_match($pattern, $input)) {
                die("WAF Detection: Blocked restricted keyword or function");
            }
        }
        return true;
    }
}

?>