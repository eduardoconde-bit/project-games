<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Registro</title>
</head>

<body id="body-login">
    <?php 
        require_once("./controller/AuthController.php");
        AuthController::processUserEvent();
    ?>
    <main>
        <section class="container-login">
            <header class="top-login">
                <h1>Cadastro</h1>
            </header>
            <div class="container-form-login">
                <form action="register.php" method="post">
                    <div class="box-input">
                        <input id="login" type="text" name="user" placeholder="Nome de usuário" required>
                    </div>
                    <div class="box-input">
                        <input id="full_name" type="text" name="full_name" placeholder="Nome Completo"  required pattern="[A-Za-zÀ-ÿ\- ]+" title="Por favor, insira apenas letras, espaços e hífens.">
                    </div>
                    <div class="box-input">
                        <input id="login" type="text" name="password_first" placeholder="Digite a senha" required>
                    </div>
                    <div class="box-input">
                        <input id="password" type="text" name="password_final" placeholder="Confirme a senha" required pattern="^[A-Za-z0-9@#$]+$">
                    </div>
                    <input type="hidden" name="action" value="register">
                    <button id="button-login" type="submit">Cadastrar</button>
                </form>
                <div class="other-authentication">
                    <span>Possui Conta? <a href="login.php">Fazer Login</a></span>
                </div>
            </div>
        </section>
    </main>
</body>

</html>