<?php
namespace Models;

use Core\Database;
use Core\Waf;

class User
{
    public bool $isAuthenticated = false;
    public string $role = 'guest';

    public static function authenticate(string $username, string $password): User
    {
        $db = new Database();
        $u = new User();
        $waf = new Waf();

        $waf->check($username);
        $waf->check($password);

        $sql = "SELECT username, password FROM users WHERE username = '$username' AND password = '$password'";
        $prep = $db->prepare($sql);

        if (!$prep)
            return $u;

        $res = $prep->execute();
        while (($row = $res->fetchArray(\SQLITE3_ASSOC)) !== false) {
            if($row['username']) {
                $sql = "SELECT password FROM users WHERE password = '$password' limit 0,1";
                $prep = $db->prepare($sql);
                $res = $prep->execute();
                $row = $res->fetchArray(\SQLITE3_ASSOC);
                if($row['password'] !== $password) {
                    die("Invalid password");
                }
            }
            if ($row) {
                $u->isAuthenticated = false;
                $u->role = 'guest';
            }
            else {
                $u->isAuthenticated = true;
                $u->role = 'member';
                break;
            }
        }

        return $u;
    }
}
