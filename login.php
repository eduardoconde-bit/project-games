<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
    <style>

    </style>
</head>
<body id="body-login">
    <?php
        require_once("./controller/AuthController.php");

        if(isset($_SESSION["isAuthenticated"])) {
            AuthController::toRedirect("index.php");
        } 
        AuthController::processUserEvent();
    ?>
    <main>
        <section class="container-login">
            <header class="top-login">
                <h1>Login</h1>
            </header>
            <div class="container-form-login">
                <form action="./login.php" method="post">
                    <div class="box-input">
                        <input id="login" type="text" name="user" placeholder="Nome de usuário" required>
                    </div>
                    <div class="box-input">
                        <input id="password" type="password" name="password"  placeholder="Digite a senha" required>
                    </div>
                    <input type="hidden" name="action" value="login">
                    <button id="button-login" type="submit">Entrar</button>
                </form>
            </div>
            <div class="other-authentication">
                <span>Não tem conta? <a href="register.php">cadastre-se</a></span>
            </div>
        </section>
    </main>
</body>
</html>