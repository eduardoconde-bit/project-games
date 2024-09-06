<?php
require_once(__DIR__ . "/services/Authenticator.php");
require_once(__DIR__. "/controller/AuthController.php");
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
    <link rel="stylesheet" href="admin.css">
    <title>Document</title>
</head>

<body>
    <header class="top">
        <h1 class="top-title">Área de Administração</h1>
            <?php if (Authenticator::isAuthenticated()) : ?>
                <div class="container-login">
                    <div class="position-dropdown">
                        <p>☰</p>
                        <div class="dropdown">
                        <a class="top-link dropdown-content" href="index.php">Home</a>
                        <a class="top-link dropdown-content" href="profile.php">Perfil</a>
                        <a class="top-link dropdown-content" href="index.php?exit=true">Sair</a>
                    </div>
                    </div>
                </div>
            <?php endif ?>
    </header>
    <main>
        <section class="container-game">
            <div class="card-game">
                <a class="option-link" href="addgame.php">
                    <img class="icon-option" src="./assets/add_icon.svg" alt="add icon">
                    <span>Adicionar Jogo</span>
                </a>
            </div>
            <div class="card-game">
                <a class="option-link" href="index.php">
                    <img class="icon-option" src="./assets/edit-icon.svg" alt="">
                    <span>Editar Jogo</span>
                </a>
            </div>
            <div class="card-game">
                <a class="option-link" href="index.php">
                    <img class="icon-option" src="./assets/icons8-remover.svg" alt="delete icon">
                    <span>Remover Jogo</span>
                </a>
            </div>
            <div class="card-game">
                <a class="option-link" href="removed.php">
                    <img class="icon-option" src="./assets/icons8-remover.svg" alt="delete icon">
                    <span>Jogos Removidos</span>
                </a>
            </div>
        </section>
    </main>
</body>
</html>