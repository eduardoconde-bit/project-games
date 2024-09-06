<?php
require_once(__DIR__ . "/controller/GameController.php");
require_once(__DIR__ . "/view/GameView.php");
if (isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Jogos Removidos</title>
</head>

<body>
    <header class="top">
        <h1 class="top-title">Jogos Removidos</h1>
    </header>
    <main>
        <div class="modal-wrapper not-visible">
            <div class="msg-w-box">
                <p class="msg-w-title">Remover Jogo?</p>
                <p class="msg-w-sub-title">Para habilitar o jogo novamente vá a área de administração e procure pela opção apropriada</p>
                <button class="button-restore" type="button">Restaurar Jogo</button>
                <button class="close-modal" type="button"></button>
            </div>
            <form id="form-restore-game" action="removed.php" method="post">
                <input type="hidden" name="action" value="game_restore">
            </form>
        </div>
        <?php GameController::processUserEvent(); ?>
        <div class="container-game">
            <?php GameView::showGameDisabled() ?>
        </div>
    </main>
    <script src="removed.js"></script>
</body>

</html>