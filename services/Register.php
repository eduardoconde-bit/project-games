<?php
require_once(__DIR__ . "/../dao/UserDAO.php");
require_once(__DIR__ . "/../model/User.php");
require_once(__DIR__ . "/PassHash.php");
include_once (__DIR__ . "/../vendor/autoload.php");

class Register
{
    /**
     * 
     */
    public static function register(string $userName, string $fullName, string $password):bool|int
    {
        try {
            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, "/../security.env");
            $dotenv->safeLoad();

            $passwordHash = PassHash::passHash($password, $_ENV["PEPPER"]);

            $user = new User($userName, $fullName, $passwordHash, "NRM");
            $userDAO = new UserDAO();
            return $userDAO->create($user);
        } catch(Exception $e) {
            echo $e;
        }
    }
}
