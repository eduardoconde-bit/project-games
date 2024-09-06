<?php
require_once(__DIR__ . "/../dao/ProducerDAO.php");

class ProducerController
{
    public static function getProducersList():array|bool
    {
        $producerDAO = new ProducerDAO();
        $producersList = $producerDAO->getAllProducers();
        if (!$producersList) {
            return false;
        }
        return $producersList;
    }
}
