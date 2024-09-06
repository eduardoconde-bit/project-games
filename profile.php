<?php
require_once("./services/Authenticator.php");
require_once("./controller/AuthController.php");
require_once("./controller/UserController.php");
if (!isset($_SESSION)) {
    session_start();
}
if (!Authenticator::isAuthenticated()) {
    AuthController::toRedirect("login.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="profile.css">
    <title>Meu Perfil</title>
</head>

<body>
    <header class="top">
        <h1 class="top-title">Perfil</h1>
        <?php
        if (isset($_GET["exit"])) {
            AuthController::logout();
        }
        ?>

        <?php if (Authenticator::isAuthenticated()) : ?>
            <div class="container-login">
                <div class="position-dropdown">
                    <p>☰</p>
                    <div class="dropdown">
                        <a class="top-link dropdown-content" href="index.php">Home</a>
                        <?php if($_SESSION["accessLevel"] === "ADM"):?>
                            <a class="top-link dropdown-content" href="admin.php">Painel Admin</a>
                        <?php endif ?>
                        <a class="top-link dropdown-content" href="index.php?exit=true">Sair</a>
                    </div>
                </div>
            </div>

        <?php else : ?>

            <div class="container-login">
                <a class="top-link" href="login.php">Entrar</a>
            </div>

        <?php endif; ?>
    </header>
    <section class="container-profile">
        <form class="form-profile" action="./profile.php" method="post">
            <div class="box-input-profile">
                <label for="user-name">Nome de Usuário*</label>
                <input id="user-name" class="disable" name="user_name" value="<?= $_SESSION["userName"] ?>" disabled></input>
            </div>
            <div class="box-input-profile">
                <label for="full-name">Nome Completo*</label>
                <input id="full-name" class="disable" name="full_name" value="<?= $_SESSION["fullName"] ?>" disabled></input>
            </div>
            <div class="box-input-profile">
                <label for="access-level">Nível de Acesso</label>
                <input id="access-level" class="disable" value="<?= $_SESSION["accessLevel"] ?> 🔐" disabled></input>
            </div>
            <div class="box-input-profile">
                <label for="password-first">Nova Senha*</label>
                <input id="password-first" class="disable" placeholder="Digite a senha" disabled></input>
            </div>
            <div class="box-input-profile">
                <label for="password-final">Confirmar Nova Senha*</label>
                <input id="password-final" class="disable" placeholder="Digite a senha novamente" disabled></input>
            </div>
            <input type="hidden" name="action" value="user_update">
            <div class="box-input-profile">
                <button id="edit-profile" type="button">Editar Informações</button>
            </div>
            <?php
            UserController::processUserEvent();
            ?>
        </form>
    </section>
    <script>
        const showEdit = () => {
            let userName = document.getElementById("user-name");
            let fullName = document.getElementById("full-name");
            //let accessLevel = document.getElementById("access-level");
            let passwordFirst = document.getElementById("password-first");
            let passwordFinal = document.getElementById("password-final");

            buttonEdit.textContent = "Confirmar";
            buttonEdit.removeEventListener("click", showEdit);
            buttonEdit.addEventListener("click", confirmUpdate);

            userName.disabled = false;
            fullName.disabled = false;
            passwordFirst.disabled = false;
            passwordFinal.disabled = false;
            //accessLevel.disabled = false;
        }

        const confirmUpdate = (event) => {
            let formProfile = document.getElementsByClassName("form-profile")[0];
            let userName = document.getElementById("user-name");
            let fullName = document.getElementById("full-name");

            if (window.confirm("Confirmar Atualizações?")) {
                formProfile.submit();
            } else {
                alert("Update Cancelado!");
            }
        }
        let formProfile = document.getElementsByClassName("form-profile")[0];
        formProfile.addEventListener("submit", formProfile);

        let buttonEdit = document.querySelector("#edit-profile");
        buttonEdit.addEventListener("click", showEdit);
    </script>
</body>

</html>