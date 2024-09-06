<?php
require_once(__DIR__ . "/DAO.php");
require_once(__DIR__ . "/ICrud.php");

class ProducerDAO extends DAO implements ICRUD
{

    /**
     * 
     */
    public function create($producer)
    {
    }

    /**
     * 
     */
    public function read($producerCod)
    {
    }

    /**
     * 
     */
    public function update($producer)
    {
    }

    /**
     * 
     */
    public function delete($producerCod)
    {
    }

    /**
     * 
     */
    public function getAllProducers():array|bool
    {
        try {
            $conn = $this->getConnection();
            $result = $conn->query("SELECT * FROM producers");
            if (!$result) {
                return false;
            }
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }
}
