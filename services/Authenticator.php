<?php
require_once("PassHash.php");
require_once(__DIR__."/../dao/UserDAO.php");
require_once(__DIR__."/../model/User.php");
include_once __DIR__ . '/../vendor/autoload.php';

/**
 * Lida com lógica de autenticação do usuário
 */

enum AccessLevel: string
{
    case ADM = 'ADM';
    case NMR = 'NMR';
}

class Authenticator
{
    /**
     * Auth user in the system
     * @param string $login
     * login identifier
     * @param string $password
     * @return bool
     * [true] - in case success or [false] in case failure in the auth 
     */
    public static function authentication(string $login, string $password):bool
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, "/../security.env");
        $dotenv->safeLoad();

        //Refatorar!
        $userDAO = new UserDAO();
        $user = $userDAO->read($login);

        //Failure or not results to user
        if(!$user || $user === -1) {
            return false;
        }

        $loginValid = ($user["user_id"] === $login);
        $passwordValid = PassHash::compareHash($password, $user["password_hash"], $_ENV["PEPPER"]);

        //Unknow/Invalid User/password failed
        if(!$loginValid || !$passwordValid) {
            return false;
        }
        if(!isset($_SESSION)) {
             session_start();
        }
        //define session to be authenticated
        $_SESSION["isAuthenticated"] = true;
        $_SESSION["accessLevel"] = $user["fk_role"] ?? "?";
        $_SESSION["userName"] = $user["user_id"] ?? "?";
        $_SESSION["fullName"] = $user["full_name"] ?? "?";
        return true;
    }

    /**
     * Verify if user is authenticated
     * @return bool
     * Return true for authenticated user or false othercase
     */
    public static function isAuthenticated():bool 
    {
        if(!isset($_SESSION["isAuthenticated"])) {
            return false;
        }
        return true;
    }

    /**
     * Check user access level
     */
    public static function accessLevel():string 
    {
        if(!self::isAuthenticated()) {
            return false;
        }
        return $_SESSION["accessLevel"];
    }
}
