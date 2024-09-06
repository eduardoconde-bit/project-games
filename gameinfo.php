<?php
    require_once "./view/GameView.php";
    if(isset($_SESSION)) {
        session_start();
    }    
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Game</title>
</head>

<body id="body-game-info">
    <header class="top">
        <div class="top-aside">
            <a onclick="history.back()" href="">
                <button class="back-page">
                </button>
            </a>
        </div>
        <div class="top-no-aside">
            <h1 class="top-title top-title-calc">Detalhes do Jogo!</h1>
        </div>
    </header>

    <main>
        <?php GameView::showGameInfo(); ?>
    </main>

    <?php require_once "./footer.php" ?>
</body>

</html>