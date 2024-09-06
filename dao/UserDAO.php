<?php
require_once(__DIR__ . "/DAO.php");
require_once(__DIR__ . "/ICrud.php");
require_once(__DIR__ . "/../model/User.php");

class UserDAO extends DAO implements ICRUD
{
    /**
     * Create User in the Database
     * @param User $user
     * A user type object for persistence in the database
     * @return bool
     * Returns true if user persistence in the database is successful or false if it fails
     * @return int
     * Returns -1 if the username of the object required for registration already exists
     */
    public function create($user)
    {
        if (!($user instanceof User)) {
            throw new Exception("The given argument is not an instance of the User class");
            die();
        }
        $userResp = $this->read($user->getUserName());
        if (!$userResp) {
            return false;
        }
        //user exists (Reformule the parameter return)
        if ($userResp !== -1) {
            return -1;
        }
        $conn = $this->getConnection();

        $user->setUserName($conn->real_escape_string($user->getUserName()));
        $user->setFullName($conn->real_escape_string($user->getFullName()));
        $user->setUserType($conn->real_escape_string($user->getUserType()));

        $query = <<<SQL
            INSERT INTO users VALUES
            ('%s', '%s', '%s', '%s')
        SQL;
        $query = sprintf($query, $user->getUserName(), $user->getFullName(), $user->getKeyword(), $user->getUserType());

        return $conn->query($query);
    }

    /**
     * Read a record of user in the database
     * @param mixed $userName
     * User Name identifier
     * @return array
     * Search success of the record 
     * @return int
     * returns the integer [-1] indicating no results for the search
     * @return bool
     * [failure] - Returns false boolean due to some failure
     */
    public function read($userName): array|int|false
    {
        if (empty($userName)) {
            return false;
        }
        try {
            $conn = $this->getConnection();
            $userName = (string) $userName;
            $userName = $conn->real_escape_string($userName);
            $result = $conn->query(" SELECT * FROM users WHERE `user_id` = '$userName' ");
            if (empty($result->num_rows)) {
                return -1;
            }
            $resultUser = $result->fetch_assoc();
            return $resultUser;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * @return bool|int
     * Return true in success case or false in failure case or user exists not possible verify. return -1 for username already exists.
     */
    public function update($user):bool|int
    {
        if (!($user instanceof User)) {
            throw new Exception("The given argument is not an instance of the User class");
            die();
        }
        $userResp = $this->read($user->getUserName());
        //Not possible verify if user name already exists or failure in query
        if (!$userResp) {
            return false;
        }
        //user exists (Reformule the parameter return)
        if ($userResp !== -1) {
            return -1;
        }

        $conn = $this->getConnection();

        $user->setUserName($conn->real_escape_string($user->getUserName()));
        $user->setFullName($conn->real_escape_string($user->getFullName()));
        //$user->setUserType($conn->real_escape_string($user->getUserType()));

        $query = <<<SQL
            UPDATE `users` SET
            `user_id` = '%s',
            `full_name` = '%s'
            WHERE `user_id` = '%s'
        SQL;

        echo $_SESSION["userName"];

        $query = sprintf($query, $user->getUserName(), $user->getFullName(), $_SESSION["userName"]);

        if(!$conn->query($query)) {
            return false;
        }
        return true;
    }

    public function delete($userName)
    {
    }

    public function searchAllUsers()
    {
    }
}
