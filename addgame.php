<?php 
    require_once(__DIR__ . "/view/GameView.php");
    require_once(__DIR__ . "/services/Authenticator.php");

    if(!isset($_SESSION)) {
        session_start();
    }

    if(!authenticator::isAuthenticated()) {
        AuthController::toRedirect("login.php");
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="edit.css">
    <link rel="stylesheet" href="profile.css">
    <title>Adicionar jogo</title>
</head>

<body>
    <header class="top">
        <h1 class="top-title">Adicionar Novo Jogo</h1>
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
        <?php endif; ?>
    </header>
    <main>
        <?php
        GameView::showAddGame();
        GameController::processUserEvent();
        ?>
    </main>
</body>

</html>