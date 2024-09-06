<?php 
    require_once("./view/GameView.php");
    require_once("./controller/AuthController.php");
    require_once("./controller/GameController.php");
    require_once("./view/GameView.php");
    if(!isset($_SESSION)) {
        session_start();
    }
    if(!authenticator::isAuthenticated()) {
        AuthController::toRedirect("login.php");
    }
?>
<!DOCTYPE html>
<html lang="pt-bt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="edit.css">
    <title>Editar Jogo</title>
</head>
<body>
    <header class="top">
        <h1 class="top-title">Editar ✍️</h1>
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
        GameController::processUserEvent(); 
        GameView::showEditGame()
        ?>
    </main>
    <script>
        let genderList = document.getElementById("gender_list");
        let producerList = document.getElementById("producer_list");
        genderList.selectedIndex = -1;
        producerList.selectedIndex = -1;
    </script>
</body>
</html>