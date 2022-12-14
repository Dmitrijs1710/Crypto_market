<?php

namespace App\Repositories;

use App\Database;
use App\Models\UserCoins\UserCoin;
use App\Models\UserCoins\UserCoinCollection;

class UserCoinFromMysql implements UserCoinRepository
{

    public function insertCoin(UserCoin $userCoin): bool
    {
        $database = Database::getConnection();
        $sql = "INSERT INTO user_coins (product_id, user_id, price, operation,count) VALUES (?,?,?,?,?)";
        $statement = $database->prepare($sql);
        $id = $userCoin->getId();
        $userId = $userCoin->getUserId();
        $price = $userCoin->getPrice();
        $operation = $userCoin->getOperation();
        $count = $userCoin->getCount();
        $statement->bind_param("sssss", $id, $userId, $price, $operation, $count);

        return($statement->execute());
    }

    public function getCoinCollectionByUserId(int $userId): UserCoinCollection
    {
        $database = Database::getConnection();

        $sql = "SELECT * FROM user_coins WHERE user_id='" . $userId . "'";
        $result = $database->query($sql);
        $userCoinCollection = (new UserCoinCollection());
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $userCoinCollection->add(new UserCoin($row['product_id'], $row['user_id'], $row['operation'], $row['price'], $row['count']));
            }
        }
        return $userCoinCollection;
    }
}