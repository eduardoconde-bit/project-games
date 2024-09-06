<?php
require_once(__DIR__ . "/IUserEvent.php");
require_once(__DIR__ . "/../dao/gameDAO.php");
require_once(__DIR__ . "/../dao/ProducerDAO.php");
require_once(__DIR__ . "/../model/Game.php");
require_once(__DIR__ . "/../services/Authenticator.php");
require_once(__DIR__ . "/../services/FileService.php");

class GameController implements IUserEvent
{
    const AWAY_COVER = "indisponivel.png";

    public static function processUserEvent()
    {

        $gameAction = $_POST["action"] ?? false;
        if ($gameAction === "game_add") {
            self::addGame();
        }
        if ($gameAction === "game_remove") {
            self::removeGame();
        }
        if ($gameAction === "game_update") {
            self::updateGame();
        }
        if ($gameAction === "game_restore") {
            self::restoreGame();
        }
    }

    //Translate View requests for searches and ordering to Model/DAO from here (this is a notification-method)
    /**
     * 
     */
    public static function processGameFilter()
    {
        //Empty value for false or normal GET assignment
        $order = $_GET["order"] ?? false;
        $search = $_GET["search"] ?? false;

        if (!$order && !$search) {
            $listGame = self::searchGameAll();
        } elseif ($order && !$search) {
            $listGame = self::searchGameAll($order);
        } elseif (!$order && $search) {
            $listGame = self::searchGameByTerm($search);
        } else {
            $listGame = self::searchGameByTerm($search, $order);
        }

        return $listGame;
    }

    /**
     * 
     */
    public static function addGame()
    {
        $gameName = $_POST["game_name"] ?? false;
        $gameDescription = $_POST["game_description"] ?? false;
        $gameGender = $_POST["game_gender"] ?? false;
        $gameProducer = $_POST["game_producer"] ?? false;
        $gameImage = self::AWAY_COVER;

        if (!($gameName && $gameDescription && $gameGender && $gameProducer)) {
            //Any View Error!
            echo "<p class='msg'>Informações requeridas estão faltando!</p>";
            return false;
        }

        //Add upload image in directory the images for to associated game;
        if (!empty($_FILES["game_image"])) {
            //Create Image Name
            $extension = FileService::getFileExtension($_FILES["game_image"]["name"]);
            $gameImage = time() . FileService::acronimString($gameName . $extension);
            $dest = __DIR__ . "/../images/" . $gameImage;

            //Try Persist in file system
            if (!FileService::saveUploadFile(userfile: "game_image", dest: $dest)) {
                $gameImage = self::AWAY_COVER;
            }
        }

        $gameDAO = new GameDAO();
        $game = new Game($gameName, $gameDescription, $gameGender, $gameProducer, cover: $gameImage);
        if (!$gameDAO->create($game)) {
            //view!!
            echo "Não foi possível adicionar novo jogo!";
        }
        //view
        echo "<p>Sucesso ao adicionar jogo</p>";
    }

    /**
     * 
     */
    public static function readGame(int $cod)
    {
        $gameDAO = new GameDAO();
        $result = $gameDAO->read($cod);
        if (!$result || $result === -1) {
            return $result;
        }
        return $result->__serialize();
    }
    /**
     * 
     */
    public static function updateGame(): bool
    {
        $gameId = $_POST["game_id"] ?? false;
        $gameName = $_POST["game_name"] ?? false;
        $gameDescription = $_POST["game_description"] ?? false;
        $gameGender = $_POST["game_gender"] ?? false;
        $gameProducer = $_POST["game_producer"] ?? false;
        $gameImage = empty($_POST["game_image"]) ? false : $_POST["game_image"];

        if (!($gameId && $gameName && $gameDescription && $gameGender && $gameProducer)) {
            //Any View Error!
            echo "<p class='msg'>Informações requeridas estão faltando!</p>";
            return false;
        }
        $game = new Game($gameName, $gameDescription, $gameGender, $gameProducer, cod: $gameId);

        $gameDAO = new GameDAO();

        if (!$gameDAO->update($game)) {
            echo "<p class='msg'>Não foi possível atualizar dados do jogo! tente novamente!</p>";
            return false;
        }
        echo "<p class='msg'>Jogo atualizado com sucesso!</p>";
        return true;
    }

    public static function removeGame()
    {
        $cod = $_POST["cod"] ?? false;
        if (!$cod) {
            //view here!
            echo "Não foi identificado código do jogo!";
        }
        $gameDAO = new GameDAO();
        if (!$gameDAO->setGameState($cod, false)) {
            //view here!
            echo "<p class=\"msg\"> >Não foi possível remover o jogo, tente novamente!</p>";
        } else {
            //view here!
            echo "<p class=\"msg\">Jogo removido com sucesso!</p>";
        }
    }

    public static function restoreGame() {
        $cod = $_POST["cod"] ?? false;
        if(!$cod) {
            echo "Não foi identificado código do jogo!";
        }
        try {
            $gameDAO = new GameDAO();
            if(!$gameDAO->setGameState($cod, true)) {
                echo "Erro! Não foi possível restaurar jogo, tente novamente";
            } else {
                echo "<p class=\"msg\" >Jogo restaurado com sucesso!</p>";
            }
        } catch (Exception $e) {
            echo "Erro! Não foi possível restaurar jogo, tente novamente";
        }
    }

    /**
     * 
     */
    public static function searchGameByTerm(string $term, false|string $fieldOrder = false): array|int|false
    {
        $gameDAO = new GameDAO();

        $term = trim($term);
        if (!empty($term)) :
            switch ($fieldOrder) {
                    //Sort by name
                case "n":
                    $result = $gameDAO->searchBy($term, GameDAO::ORDER_BY_NAME);
                    break;
                    //Sort by producer
                case "p":
                    $result = $gameDAO->searchBy($term, GameDAO::ORDER_BY_PRODUCER);
                    break;
                    //Sort by ascending rank
                case "ra":
                    $result = $gameDAO->searchBy($term, GameDAO::ORDER_BY_RATING);
                    break;
                    //Sort by ascending rank
                case "rd":
                    $result = $gameDAO->searchBy($term, GameDAO::ORDER_BY_RATING, GameDAO::DESC);
                    break;
                    //Default Search
                default:
                    $result = $gameDAO->searchBy($term);
            }
        else :
            // empty term searches all games 
            $result = $gameDAO->readAll(GameDAO::JOIN);
        endif;

        return self::processGameList($result);
    }

    /**
     * 
     */
    public static function searchGameAll(?string $fieldOrder = null): array
    {
        $gameDAO = new GameDAO();

        switch ($fieldOrder) {
            case "n":
                $result = $gameDAO->readAll(GameDAO::JOIN, GameDAO::ORDER_BY_NAME);
                break;
            case "p":
                $result = $gameDAO->readAll(GameDAO::JOIN, GameDAO::ORDER_BY_PRODUCER);
                break;
            case "ra":
                $result = $gameDAO->readAll(GameDAO::JOIN, GameDAO::ORDER_BY_RATING);
                break;
            case "rd":
                $result = $gameDAO->readAll(GameDAO::JOIN, GameDAO::ORDER_BY_RATING, GameDAO::DESC);
                break;
            default:
                $result = $gameDAO->readAll(GameDAO::JOIN);
        }

        return self::processGameList($result);
    }

    public static function searchDisabledGame()
    {
        try {
            $conn = new GameDAO();
            $result = $conn->readAllDisabled();
            return self::processGameList($result, false);
        } catch (Exception $e) {
            echo "Erro!";
        }
    }

    /**
     * 
     */
    private static function processGameList(array|int|false $resultGame, bool $gameActive = true): array|int|false
    {
        //False or flag -1 (Not records in the search database)
        if (!$resultGame || $resultGame === -1) {
            return $resultGame;
        }
        //if logged user adm;
        if (Authenticator::isAuthenticated() && Authenticator::accessLevel() === AccessLevel::ADM->name) {
            foreach ($resultGame as $game) {
                $game = $game->__serialize();
                $game["isAdmin"] = true;
                $listGame[] = $game;
            }
        //Not Adm user
        } else {
            foreach ($resultGame as $game) {
                $listGame[] = $game->__serialize();
            }
        }
        return $listGame;
    }
}
