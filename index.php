<?php

if (!isset($_SESSION)) {
    session_start();
}
if (isset($_GET["exit"])) {
    session_destroy();
    header("Location: index.php");
}
require_once "./services/Authenticator.php";
require_once "./view/GameView.php";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" sizes="any" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🕹️</text></svg>" />
    <link rel="stylesheet" href="style.css">
    <title>Games</title>
</head>

<body>
    <div class="modal-wrapper not-visible">
        <div class="msg-w-box">
            <p class="msg-w-title">Remover Jogo?</p>
            <p class="msg-w-sub-title">Para habilitar o jogo novamente vá a área de administração e procure pela opção apropriada</p>
            <button class="button-remove" type="button">Desabilitar Jogo</button>
            <button class="close-modal" type="button"></button>
        </div>
        <form id="form-remove-game" action="index.php" method="post">
            <input type="hidden" name="action" value="game_remove">
        </form>
    </div>
    <section class="section-game">
        <header class="top">
            <h1 class="top-title">LISTA DE JOGOS</h1>
            <?php if (Authenticator::isAuthenticated()) : ?>
                <div class="container-login">
                    <div class="position-dropdown">
                        <p>☰</p>
                        <div class="dropdown">
                            <a class="top-link dropdown-content" href="profile.php">Perfil</a>
                            <?php if ($_SESSION["accessLevel"] === "ADM") : ?>
                                <a class="top-link dropdown-content" href="admin.php">Painel Admin</a>
                            <?php endif ?>
                            <a class="top-link dropdown-content" href="index.php?exit=true">Sair</a>
                        </div>
                    </div>
                </div>

            <?php else : ?>

                <div class="menu-not-login">
                    <a class="top-link" href="login.php">Entrar</a>
                </div>

            <?php endif; ?>

        </header>
        <form action="index.php" method="get">
            <div class="container-form">
                <div class="order-container">
                    <span>Ordernar por:</span>
                    <a class="nav-link" href="index.php?order=n&search=<?= ($_GET["search"] ?? "") ?>">Nome</a>
                    <a class="nav-link" href="index.php?order=p&search=<?= ($_GET["search"] ?? "") ?>">Produtora</a>
                    <a class="nav-link" href="index.php?order=ra&search=<?= ($_GET["search"] ?? "") ?>">Nota Baixa</a>
                    <a class="nav-link" href="index.php?order=rd&search=<?= ($_GET["search"] ?? "") ?>">Nota Alta</a>
                    <a class="nav-link" href="index.php">Exibir todos</a>
                </div>
                <div class="search-container">
                    <label class="menu-label" for="search">Buscar</label>
                    <input id="search" type="search" name="search" required>
                    <button class="button-form" type="submit">Ir</button>
                </div>
            </div>
        </form>
        <?php GameController::processUserEvent() ?>
        <div class="container-game">
            <?php GameView::showGames() ?>
        </div>
    </section>

    <?php require_once "./footer.php"; ?>
    <script src="index.js"></script>
</body>

</html>