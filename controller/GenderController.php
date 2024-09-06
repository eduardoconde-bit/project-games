<?php
require_once(__DIR__ . "/../dao/GenderDAO.php");

class GenderController
{

    public static function getAllGenders()
    {
        $genderDAO = new GenderDAO();
        $result = $genderDAO->readAllGenders();

        if (!$result) {
            return false;
        }

        foreach ($result as $gender) {
            $genderList[] = $gender->__serialize();
        }
        return $genderList;
    }
}
