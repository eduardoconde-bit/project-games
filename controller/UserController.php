<?php
if(!isset($_SESSION)) {
    session_start();
}

require_once(__DIR__."/IUserEvent.php");
require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../dao/UserDAO.php");
require_once(__DIR__."/../view/UserView.php");
require_once(__DIR__."/AuthController.php");

class UserController implements IUserEvent {
    public static function processUserEvent()
    {
        $updateUserEvent = $_POST["action"] ?? false;
        if($updateUserEvent === "user_update") {
            self::userUpdate();
        }
    }
    
    public static function userUpdate() {
        $user = new User($_POST["user_name"], $_POST["full_name"], null, $_SESSION["accessLevel"]);
        $isUpdate = (new UserDAO())->update($user);
        echo $isUpdate;
        if(!$isUpdate) {
            UserView::msgFailedUpdate();
        }
        if($isUpdate === -1) {
            UserView::msgNameExists();
        } else {
            echo "Sucesso ao atualizar!";
            $_SESSION["userName"] = $_POST["user_name"];
            AuthController::logout();                    
        }
        unset($_POST);
    }
}