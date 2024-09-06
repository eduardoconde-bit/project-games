<?php
//loading module dotenv
include_once __DIR__ . '/../vendor/autoload.php';

//Load the Credentials of the database of the environment
$database_credentials = Dotenv\Dotenv::createImmutable(__DIR__."/../", "database.env");
$database_credentials->safeLoad();

/**
 * Base Class for to Access Database
 */
class DAO
{

    protected readonly ?string $hostname;
    protected readonly ?string $username;
    protected readonly ?string $password;
    protected readonly ?string $database;
    protected ?int $port = null;
    protected ?string $socket = null;
    protected mysqli $instanceConnection;

    public function __construct()
    {
        $this->hostname = $_ENV["HOSTNAME"];
        $this->username = $_ENV["USERNAME"];
        $this->password = $_ENV["PASSWORD"];
        $this->database = $_ENV["DATABASE"];
    }

    public function getConnection()
    {
        $this->instanceConnection = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        $this->instanceConnection->set_charset("utf8mb4");
        return $this->instanceConnection;
    }
}
