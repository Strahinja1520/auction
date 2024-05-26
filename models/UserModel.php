<?php
namespace App\Models;



class UserModel
{
    private $db;

    public function __construct(\App\Core\DatabaseConnection &$dbc)
    {
        $this->db = $dbc->getConnection();
    }

    public function getAllUsers()
    {
        $sql = "SELECT * FROM user WHERE is_admin = 0 AND is_moderator = 0";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute();
        $users = [];
        if($res){
            $users = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $users;
    }

    public function getModerators(){
        $sql = "SELECT * FROM user WHERE is_moderator = true";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute();
        $users = [];
        if($res){
            $users = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $users;
    }

    public function getUserById(int $userid)
    {
        $sql = "SELECT * FROM user WHERE user_id = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$userid]);
        $user = NULL;
        if($res){
            $user = $prep->fetch(\PDO::FETCH_OBJ);
        }

        return $user;
    }

    public function getUserByName(string $username)
    {
        $sql = "SELECT * FROM user WHERE username = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$username]);
        $user = NULL;
        if($res){
            $user = $prep->fetch(\PDO::FETCH_OBJ);
        }

        return $user;
    }
    public function getUserByEmail(string $userEmail)
    {
        $sql = "SELECT * FROM user WHERE email = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$userEmail]);
        $user = NULL;
        if($res){
            $user = $prep->fetch(\PDO::FETCH_OBJ);
        }

        return $user;
    }

    public function insertUser(string $forename, string $surname, string $phone, string $email, string $username, string $password, int $is_admin, int $is_moderator): bool
    {
        $sql = "INSERT INTO user (forename, surname, phone, email, username, password_hash, is_admin, is_moderator) VALUES(?,?,?,?,?,?,?,?)";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$forename, $surname, $phone, $email, $username, $password, $is_admin, $is_moderator]);

        return $res;
    }

    public function editUser(int $userId, string $email, string $fname, string $lname, string $phone, string $username): bool
    {
        // UPDATE `user` SET `address` = 'Zikicina 5a', `phone` = '+381 064551122', `username` = 'zile8' WHERE `user`.`user_id` = 10;
        $sql = "UPDATE user SET email = ?, forename = ?, surname = ?, phone = ?, username = ? WHERE user_id = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$email, $fname, $lname, $phone, $username, $userId]);
        return $res;
    }

    public function deleteUser(int $id){
        $sql = "DELETE FROM user WHERE user_id = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$id]);
        return $res;
    }
}