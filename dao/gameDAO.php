<?php
require_once "ICrud.php";
require_once __DIR__ . "/../model/Game.php";
require_once __DIR__ . "/DAO.php";

class GameDAO extends DAO implements ICRUD
{
    //Read All Game with/not join sql (I will reformulate to bool value)
    const NO_JOIN = 0;
    const JOIN = 1;

    /*
        Order - Numeric Position of field in the query
        and keyord DESC
    */
    const ORDER_BY_COD = 1;
    const ORDER_BY_NAME = 2;
    const ORDER_BY_PRODUCER = 7;
    const ORDER_BY_RATING = 4;
    const DESC = "DESC";
    const ASC = "ASC";

    /**
     * 
     */
    public function create($game): bool
    {
        try {
            if (!($game instanceof Game)) {
                throw new Exception("The given argument is not an instance of the Game class");
                return false;
            }
            //Returns false if the id value cannot be guaranteed
            $maxID = $this->getMaxGameID();
            if ($maxID === false) {
                return false;
            }
            //0 == false;
            $game->setCod($maxID ? $maxID + 1 : 1);

            $conn = $this->getConnection();

            //Escape Values fot the query string
            $game->setCod($conn->real_escape_string($game->getCod()));
            $game->setName($conn->real_escape_string($game->getName()));
            $game->description = $conn->real_escape_string($game->description);
            $game->setCover($conn->real_escape_string($game->getCover()));
            $game->setGender($conn->real_escape_string($game->getGender()));
            $game->setProducer($conn->real_escape_string($game->getProducer()));

            $query = <<<SQL
               INSERT INTO `games` VALUES ('%s', '%s', '%s', NULL, '%s', '%s', '%s', DEFAULT); 
            SQL;
            //adding the query values
            $query = sprintf($query, $game->getCod(), $game->getName(), $game->description, $game->getCover(), $game->getGender(), $game->getProducer());

            $result = $conn->query($query);

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function read($cod): Game|int|false
    {
        try {
            $conn = $this->getConnection();
            $cod = (string) $cod;
            $cod = $conn->real_escape_string($cod);

            $query = <<<SQL
                SELECT ga.id, ga.game_name, ga.game_description, ga.rating, ga.cover, ge.gender, p.producer FROM games ga JOIN genders ge ON ga.fk_gender = ge.id JOIN producers p ON ga.fk_producer = p.id WHERE ga.id = {$cod};
            SQL;

            $result = $conn->query($query);

            //query failed for some reason
            if (!$result) {
                return false;
            }
            //The searched record does not exist in the Database
            if (empty($result->num_rows)) {
                return -1;
            }
            //Success in search with record found
            $result = $result->fetch_array(MYSQLI_ASSOC);
            $conn->close();
            return new Game($result["game_name"], $result["game_description"], $result["gender"], $result["producer"], $result["rating"], $result["cover"], $result["id"]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($game): bool
    {
        if (!($game instanceof Game)) {
            throw new Exception("The given argument is not an instance of the Game class");
            return false;
        }
        try {
            $conn = $this->getConnection();

            $query = <<<SQL
                UPDATE `games` SET
                `game_name` = '%s',
                `game_description` = '%s',
                `fk_gender` = '%s',
                `fk_producer` = '%s'
                WHERE `id` = '%s'
            SQL;

            $query = sprintf($query, $game->getName(), $game->description, $game->getGender(), $game->getProducer(), $game->getCod());
            $result  = $conn->query($query);
            if (!$result) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function delete($cod)
    {
    }

    /**
     * @param string $cod
     * cod for the game
     * @param bool $state
     * active state of the game, enabled in true and unabled in false;  
     */
    public function setGameState(string $cod, bool $state): bool
    {
        try {
            $state = $state ? 1 : 0;
            $conn = $this->getConnection();
            $cod = $conn->real_escape_string($cod);
            $game = $this->read($cod);

            if (!$game || $game === -1) {
                return false;
            }

            $result = $conn->query("UPDATE `games` SET `is_active` = $state WHERE `id` = '$cod' ");
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 
     */
    public function getMaxGameID(): string|bool|null
    {
        try {
            $conn = $this->getConnection();
            $result = $conn->query("SELECT MAX(`id`) AS max_id FROM games");
            if (!$result) {
                return false;
            }
            if (!($result->num_rows >  0)) {
                return null;
            }
            $result = $result->fetch_assoc();
            return $result["max_id"];
        } catch (Exception $e) {
            return false;
        }
    }

    public function readAll(int $optionInner = GameDAO::NO_JOIN, int $fieldOrder = GameDAO::ORDER_BY_COD, string $order = GameDAO::ASC): array|false|int
    {
        try {
            $conn = $this->getConnection();

            $fieldOrder = $conn->real_escape_string($fieldOrder);
            $order = $conn->real_escape_string($order);

            //Join query
            $queryInner = sprintf("select ga.id, ga.game_name, ga.game_description, ga.rating, ga.cover, ge.gender, p.producer, ga.is_active from games ga join genders ge on ga.fk_gender = ge.id join producers p on ga.fk_producer = p.id where ga.is_active = true order by %s %s", $fieldOrder, $order);

            switch ($optionInner) {
                case GameDAO::NO_JOIN:
                    $result = $conn->query("SELECT * FROM `games`");
                    break;
                case GameDAO::JOIN:
                    $result = $conn->query($queryInner);
                    break;
            }

            return $this->processGameList($result);
        } catch (Exception $e) {
            die($e);
        }
    }

    //Search records by specific term
    public function searchBy(string $term, int $fieldOrder = GameDAO::ORDER_BY_COD, string $order = GameDAO::ASC): array|int|false
    {
        $result = null;
        try {
            $conn = $this->getConnection();

            $term = $conn->real_escape_string($term);

            $queryInner = <<<SQL
                select 
                    ga.id, ga.game_name, ga.game_description, ga.rating, ga.cover, ge.gender, p.producer, ga.is_active 
                from 
                    games ga 
                join 
                    genders ge on ga.fk_gender = ge.id 
                join 
                    producers p on ga.fk_producer = p.id 
                where 
                    ga.game_name like '%$term%' or 
                    p.producer like '%$term%' or 
                    ge.gender like '%$term%' 
                order by 
                    $fieldOrder $order
            SQL;

            $result = $conn->query($queryInner);

            return $this->processGameList($result);
        } catch (Exception $e) {
            die($e);
        }
    }

    public function readAllDisabled():array|int|bool
    {
        $query = sprintf("select ga.id, ga.game_name, ga.game_description, ga.rating, ga.cover, ge.gender, p.producer, ga.is_active from games ga join genders ge on ga.fk_gender = ge.id join producers p on ga.fk_producer = p.id where ga.is_active = false ");

        try {
            $conn = $this->getConnection();
            $result = $conn->query($query);
            return $this->processGameList($result); 
        } catch(Exception $e) {
            return false;
        }
    }

    /**
     * 
     */
    public function processGameList(\mysqli_result|bool $result)
    {
        $gameList = array();

        if (!$result) {
            return false;
        }
        if (empty($result->num_rows)) {
            return -1;
        }
        foreach ($result as $game) {
            $gameList[] = new Game($game["game_name"], $game["game_description"], $game["gender"], $game["producer"], $game["rating"], $game["cover"], $game["id"], $game["is_active"]);
        }
        return $gameList;
    }
}
