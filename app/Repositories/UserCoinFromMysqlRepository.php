<?php

namespace App\Repositories;

use App\Database;
use App\Models\Collections\UserCoinCollection;
use App\Models\UserCoin;
use App\Models\UserCoinProperties\TransactionData;
use DateTime;
use Exception;

class UserCoinFromMysqlRepository implements UserCoinRepository
{

    public function insertCoin(UserCoin $userCoin): bool
    {
        $database = Database::getConnection();
        $sql = "INSERT INTO user_coins (product_id, user_id, price, operation,count,logo,name,symbol,date,userIdTo,open_short) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $statement = $database->prepare($sql);
        $id = $userCoin->getProductId();
        $userId = $userCoin->getUserId();
        $price = $userCoin->getPrice();
        $operation = $userCoin->getUserCoinTransaction()->getOperationType();
        $count = $userCoin->getUserCoinTransaction()->getAmount();
        $logo = $userCoin->getLogo();
        $name = $userCoin->getName();
        $symbol = $userCoin->getSymbol();
        $date = $userCoin->getUserCoinTransaction()->getDate()->format('Y-m-d H:i:s');
        $userIdTo = $userCoin->getUserCoinTransaction()->getUserTo();
        $open = $userCoin->getUserCoinTransaction()->getOpen();
        $statement->bind_param(
            "sssssssssss",
            $id,
            $userId,
            $price,
            $operation,
            $count,
            $logo,
            $name,
            $symbol,
            $date,
            $userIdTo,
            $open
        );
        return ($statement->execute());
    }


    /**
     * @throws Exception
     */
    public function getCoinCollectionByUserId(int $userId): UserCoinCollection
    {
        $database = Database::getConnection();

        $sql = "SELECT * FROM user_coins WHERE user_id='" . $userId . "' or userIdTo='" . $userId . "'";
        $result = $database->query($sql);
        $userCoinCollection = (new UserCoinCollection());
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userCoinCollection->add(
                    new UserCoin(
                        $row['product_id'],
                        $row['user_id'],
                        $row['price'],
                        $row['logo'],
                        $row['name'],
                        $row['symbol'],
                        new TransactionData($row['operation'], new DateTime($row['date']), $row['count'], $row['userIdTo'], $row['open_short']),
                        $row['id']
                    ));
            }
        }
        return $userCoinCollection;
    }

    public function getUserCoinByUniqueId(int $coinUniqueId): ?UserCoin
    {
        $database = Database::getConnection();

        $sql = "SELECT * FROM user_coins WHERE id='" . $coinUniqueId . "'";
        $result = $database->query($sql);
        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                return new UserCoin(
                    $row['product_id'],
                    $row['user_id'],
                    $row['price'],
                    $row['logo'],
                    $row['name'],
                    $row['symbol'],
                    new TransactionData($row['operation'], new DateTime($row['date']), $row['count'], $row['userIdTo'], $row['open_short']),
                    $row['id'],
                );
            }
        }
        return null;
    }
    public function updateCoin(string $field, string $value, int $id): bool
    {
        $database = Database::getConnection();

        $sql = "UPDATE user_coins SET $field='$value' WHERE id='$id'";
        if ($database->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}