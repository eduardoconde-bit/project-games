<?php
require_once __DIR__ . "/../services/Authenticator.php";
require_once __DIR__ . "/../services/Register.php";
require_once __DIR__ . "/IUserEvent.php";

    if(!isset($_SESSION)) {
        session_start();
    }

//Login and Register
class AuthController implements IUserEvent
{

    public static function processUserEvent()
    {
        $actionAuth = $_POST["action"] ?? false;

        switch ($actionAuth) {
            case "register":

                $login = $_POST["user"] ?? false;
                $fullName = $_POST["full_name"] ?? false;
                $passwordFirst = $_POST["password_first"] ?? false;
                $passwordFinal = $_POST["password_final"] ?? false;
                //Executa Se pelo menos 1 entrada for falsa
                if (!$login || !$fullName || !$passwordFirst || !$passwordFinal) {
                    echo <<<SCRIPT
                        <script>
                            alert("Insira valores nos campos!");
                        </script>
                    SCRIPT;
                }
                echo <<<SCRIPT
                        <script>
                            alert("Tudo Certo com register!");
                        </script>
                    SCRIPT;

                self::actionRegister($login, $fullName, $passwordFinal);
                /**/
                break;

                //Logging 
            case "login":
                $userName = $_POST["user"] ?? false;
                $password = $_POST["password"] ?? false;

                if (!$userName || !$password) {
                    echo <<<SCRIPT
                        <script>
                            alert("Insira seu nome de usuário e senha!");
                        </script>
                    SCRIPT;
                    exit();
                }
                if (!self::actionLogin($userName, $password)) {
                    echo <<<SCRIPT
                        <script>
                            alert("Login ou Senha Inválidos! Tente Novamente");
                        </script>
                    SCRIPT;
                } else {
                    self::toRedirect("index.php");
                }
                break;
        }
    }

    public static function actionLogin(string $login, string $password, string $email = null)
    {
        if (empty($login) || empty($password)) {
            return false;
        }
        return Authenticator::authentication($login, $password);
    }

    //alias to header() with Locale: *.*
    public static function toRedirect(string $locale = null): void
    {
        if (!empty($locale)) {
            header("Location: $locale");
        }
    }

    public static function actionRegister(string $userName, string $fullName, string $password):bool
    {
        $userName = trim($userName);
        $fullName = trim($fullName);
        $isRegister = Register::register($userName, $fullName, $password);
        var_dump($_SESSION);
        //Substitua pelas views apropriadas
        if (!$isRegister) {
            echo <<<SCRIPT
                <script>
                    alert("Não foi possível efetuar cadastro, tente novamente!");
                </script>
            SCRIPT;
            return false; 
        }
        if ($isRegister === -1) {
            echo <<<HTML
                <p><strong>Nome de usuário já existe, tente outro por favor</strong></p>
            HTML;
            return false;
        }
        echo <<<HTML
            <p><strong>Tudo Certo!</strong></p>
        HTML;
        $isLogin = self::actionLogin($userName, $password);
        sleep(1);
        var_dump($isLogin);
        var_dump($_SESSION);
        if(!$isLogin) {
            self::toRedirect("login.php");
        } else {
            self::toRedirect("index.php");
        }
        return true;
    }

    public static function logout(bool $toIndex = true) {
        session_destroy();
        if($toIndex) {
            header("Location: index.php"); 
        }
    }
}
