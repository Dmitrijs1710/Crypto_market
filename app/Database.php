<?php

namespace App;

use mysqli;

class Database
{
    private static ?mysqli $connection = null;

    public static function getConnection() :mysqli
    {
        if (self::$connection===null){
            $databaseServername = $_ENV['DB_ADDRESS'];
            $databaseUsername = $_ENV['DB_USERNAME'];
            $databasePassword = $_ENV['DB_PASSWORD'];
            $databaseTable = $_ENV['DB_TABLE'];


            self::$connection = new mysqli($databaseServername, $databaseUsername, $databasePassword, $databaseTable);
        }
        return self::$connection;
    }
}