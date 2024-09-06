<?php
require_once(__DIR__ . "/../controller/GameController.php");
require_once(__DIR__ . "/../controller/ProducerController.php");
require_once(__DIR__ . "/../controller/GenderController.php");

$htmlGame = <<<HTML
    <div class="block">
        <img src="" alt="">
        <p class="paragraph">Foto</p>
    </div>
HTML;

const GAME_NOT_FOUND = <<<HTML
    
HTML;

class GameView
{

    public static function showGames()
    {

        $listGame = GameController::processGameFilter();

        if (!$listGame) {
            die("<p class=\"msg-failed\">Não foi possível carregar os jogos!</p>");
        }

        if ($listGame == -1) {
            die("<p class=\"msg-null-game\">Nenhum Jogo Encontrado!</p>");
        }

        foreach ($listGame as $game) {
            if (isset($_SESSION["accessLevel"]) && $_SESSION["accessLevel"] === "ADM") {
                $htmlLoggedIn = <<<HTML
                    <div>
                        <a class="card-game-option" href="edit.php?cod={$game['cod']}">
                            <img class="icon-edit" src="/assets/edit-icon.svg" alt="">
                        </a>
                        <button class="card-game-option game-remove" href="#" data-cod="{$game['cod']}">
                            <img class="icon-edit" src="/assets/icons8-remover.svg" alt="remover">
                        </button>
                    </div>
                HTML;
            } else {
                $htmlLoggedIn = null;
            }

            $htmlGame = <<<HTML
                <div class="card-game">
                    <a class="link-game" href="gameinfo.php?cod=%s">
                        <div class="">
                            <p class="card-game-title">%s</p>
                            <figure class="game-image">
                                <img src="/images/%s" alt="">
                            </figure>
                            <p class="card-game-bottom-info">%s</p>
                            <p class="card-game-bottom-info">%s</p>
                            <p class="card-game-bottom-info">Nota: <span>%s</span></p>      
                        </div>
                    </a>
                    %s
                </div>
            HTML;

            $htmlGame = sprintf($htmlGame, $game["cod"], $game["name"], $game["cover"], $game["gender"], $game["producer"], $game["rating"], $htmlLoggedIn);

            echo $htmlGame;
        }
    }

    public static function showGameDisabled() {
        $listGame = GameController::searchDisabledGame();

        if (!$listGame) {
            die("<p class=\"msg-failed\">Não foi possível carregar os jogos!</p>");
        }

        if ($listGame == -1) {
            die("<p class=\"msg-null-game\">Nenhum Jogo Encontrado!</p>");
        }

        foreach ($listGame as $game) {
            $htmlGame = <<<HTML
                <div class="card-game">
                    <a class="link-game" href="gameinfo.php?cod=%s">
                        <div class="">
                            <p class="card-game-title">%s</p>
                            <figure class="game-image">
                                <img src="/images/%s" alt="">
                            </figure>
                            <p class="card-game-bottom-info">%s</p>
                            <p class="card-game-bottom-info">%s</p>
                            <p class="card-game-bottom-info">Nota: <span>%s</span></p>      
                        </div>
                    </a>
                    <div>
                        <button class="card-game-option game-restore" href="#" data-cod="{$game['cod']}">
                            <img class="icon-edit" src="/assets/restore-icon.svg" alt="remover">
                        </button>
                    </div>
                </div>
            HTML;

            $htmlGame = sprintf($htmlGame, $game["cod"], $game["name"], $game["cover"], $game["gender"], $game["producer"], $game["rating"]);

            echo $htmlGame;
        }
    }

    public static function showGameInfo()
    {
        if (!isset($_GET["cod"])) {
            die("<script>alert(\"Parâmetro de Busca não Informado!\")</script>");
        }

        $game = GameController::readGame($_GET["cod"]);

        if (!$game) {
            echo "<p class=\"msg-failed\">Não foi possível carregar o jogo!</p>";
            die;
        }

        if ($game === -1) {
            echo "<p class=\"msg msg-null-game\">Jogo não Encontrado!</p>";
            die;
        }

        $htmlGameDetailed = <<<HTML
            <div class="card-game-detailed">
                <div>
                    <figure class="game-image-full">
                        <img src="/images/%s" alt="">
                        <div class="container-bottom-info">
                            <p>%s</p>
                            <p>%s</p>
                            <p>Nota: %s</p>
                        </div>
                    </figure>
                </div>
                <div class="container-g-text">
                    <h2 class="title-g-detailed">%s</h2>
                    <p>%s</p>
                </div>
            </div>
        HTML;

        $htmlGame = sprintf($htmlGameDetailed, $game["cover"], $game["gender"], $game["producer"], $game["rating"] ,$game["name"], $game["description"]);

        echo $htmlGame;
    }

    public static function showEditGame()
    {

        if (!isset($_GET["cod"])) {
            die("<script>alert(\"Parâmetro de Busca não Informado!\")</script>");
        }

        $game = GameController::readGame($_GET["cod"]);

        if (!$game) {
            echo "<p class=\"msg-failed\">Não foi possível carregar o jogo!</p>";
            die;
        }

        if ($game === -1) {
            echo "<p class=\"msg-null-game\">Jogo não Encontrado!</p>";
            die;
        }

        //Gender option List
        $gendersListHTML = "";
        $genderOption = <<<HTML
            <option value="%s">%s</option>
        HTML;

        $genderList = GenderController::getAllGenders();
        foreach ($genderList as $gender) {
            $gendersListHTML .= sprintf($genderOption, $gender["cod"], $gender["gender"]);
        }

        //Producer option List
        $producersListHTML = "";
        $producerOption = <<<HTML
            <option value="%s">%s</option>
        HTML;

        $producerList = ProducerController::getProducersList();
        foreach ($producerList as $producer) {
            $producersListHTML .= sprintf($producerOption, $producer["id"], $producer["producer"]);
        }

        $html = <<<HTML
            <section class="container-edit">
            <form class="edit-form" action="edit.php?cod={$game['cod']}" method="post">
                <div class="box-input-profile">
                    <label for="game-name">Nome do jogo</label>
                    <input id="game-name" name="game_name" class="disable" value="{$game['name']}" required></input>
                </div>
                <div class="box-input-profile">
                    <label for="description-game">Descrição</label>
                    <textarea name="game_description" id="description-game">{$game['description']}</textarea>
                </div>
                <div class="box-input-profile">
                    <label for="gender">Gênero Atual</label>
                    <input id="gender" list="gender-list" value="{$game['gender']}" type="text" disabled></input>
                </div>
                <div class="box-input-profile">
                    <label for="gender_list"> Escolher Gênero</label>
                    <select name="game_gender" id="gender_list" required>
                        %s
                    </select>
                <div class="box-input-profile">
                    <label for="producer-now">Produtora Atual</label>
                    <input id="producer-now" type="text" value="{$game['producer']}" disabled>
                </div>
                <div class="box-input-profile">
                    <label for="producer_list">Escolher Produtora?</label>
                    <select name="game_producer" id="producer_list" required>
                        %s
                    </select>
                </div>
                <div class="box-input-profile">
                    <label for="img-input">Nova imagem para o jogo</label>
                    <input id="img-input" name="game_image" type="file" accept="image/png, image/jpeg">
                </div>
                <input type="hidden" name="action" value="game_update">
                <input type="hidden" name="game_id" value="{$game['cod']}">
                <button id="edit-button" type="submit">Confirmar</button>
            </form>
        </section>
        HTML;

        $html = sprintf($html, $gendersListHTML, $producersListHTML);
        echo $html;
    }

    public static function showAddGame() {
        //Gender option List
        $gendersListHTML = "";
        $genderOption = <<<HTML
            <option value="%s">%s</option>
        HTML;

        $genderList = GenderController::getAllGenders();
        foreach ($genderList as $gender) {
            $gendersListHTML .= sprintf($genderOption, $gender["cod"], $gender["gender"]);
        }

        //Producer option List
        $producersListHTML = "";
        $producerOption = <<<HTML
            <option value="%s">%s</option>
        HTML;

        $producerList = ProducerController::getProducersList();
        foreach ($producerList as $producer) {
            $producersListHTML .= sprintf($producerOption, $producer["id"], $producer["producer"]);
        }

        $html = <<<HTML
            <section class="container-edit">
            <form class="edit-form" action="addgame.php" method="post" enctype="multipart/form-data">
                <div class="box-input-profile">
                    <label for="game-name">Nome do jogo</label>
                    <input id="game-name" name="game_name" class="disable" required></input>
                </div>
                <div class="box-input-profile">
                    <label for="description-game">Descrição</label>
                    <textarea name="game_description" id="description-game" required></textarea>
                </div>
                <div class="box-input-profile">
                    <label for="gender_list"> Escolher Gênero</label>
                    <select name="game_gender" id="gender_list" required>
                        %s
                    </select>
                <div class="box-input-profile">
                    <label for="producer_list">Escolher Produtora?</label>
                    <select name="game_producer" id="producer_list" required>
                        %s
                    </select>
                </div>
                <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                <div class="box-input-profile">
                    <label for="img-input">Imagem para o jogo</label>
                    <input id="img-input" name="game_image" type="file" accept="image/png, image/jpeg">
                </div>
                <input type="hidden" name="action" value="game_add">
                <button id="create-button" type="submit">Confirmar</button>
            </form>
        </section>
        HTML;

        $html = sprintf($html, $gendersListHTML, $producersListHTML);
        echo $html;
    }
}
