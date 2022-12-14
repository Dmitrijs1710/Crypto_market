<?php

namespace App\Repositories;

use App\Database;
use App\Models\User;
use App\Models\UserCoins\UserCoinCollection;

class UserFromMysqlRepository implements UsersRepository
{

    public function getUserById(int $id): ?User
    {
        $database = Database::getConnection();
        $sql = "SELECT * FROM users WHERE id='" . $id . "'";
        $result = $database->query($sql);
        if ($result->num_rows === 1) {
            $data = $result->fetch_assoc();
            return new User($data['email'], $data['name'], $data['balance'], null, $data['id']);
        }
        return null;
    }

    public function insertUser(User $user): ?string
    {
        $database = Database::getConnection();
// Check connection

        $sql = "INSERT INTO users (email, name, password, balance) VALUES (?,?,?,?)";
        $statement = $database->prepare($sql);
        $password_hash = password_hash($user->getPassword(), PASSWORD_DEFAULT, []);
        $EMail = $user->getEMail();
        $name = $user->getName();
        $balance = 0;
        $statement->bind_param("ssss", $EMail, $name, $password_hash, $balance);
        if (!$statement->execute()) {
            return null;
        } else {
            return $database->insert_id;
        }
    }

    public function updateUserInformation(string $field, string $value, string $id): bool
    {
        $database = Database::getConnection();

        $sql = "UPDATE users SET $field='$value' WHERE id='$id'";
        if ($database->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserByEmail(string $email): ?User
    {
        $database = Database::getConnection();

        $sql = "SELECT id,email, name, password FROM users WHERE email='" . $email . "'";
        $result = $database->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new User($row['email'], $row['name'], 0, null, $row['password'], $row['id']);
        }
        return null;
    }

    public function addUserCoins(int $userId, UserCoinCollection $userCoins): bool
    {
        return false;
    }
}