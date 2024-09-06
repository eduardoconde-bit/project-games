<?php
require_once (__DIR__."/DAO.php");
require_once(__DIR__."/ICrud.php");
require_once(__DIR__."/../model/Gender.php");

class GenderDAO extends DAO implements ICRUD
{
    public function create($gender)
    {
    }

    public function read($genderCod)
    {
    }

    public function update($gender)
    {
    }

    public function delete($genderCod)
    {
    }

    public function readAllGenders()
    {
        try {
            $conn = $this->getConnection();
            $result = $conn->query("SELECT * FROM `genders` ORDER BY `gender` ASC");
            if(!$result) {
                return false;
            }
            $result = $result->fetch_all(MYSQLI_ASSOC);
            foreach($result as $gender) {
                $genderList[] = new Gender($gender["gender"], $gender["id"]);
            }
            return $genderList;
        } catch(Exception $e) {
            return false;
        }
    }
}
