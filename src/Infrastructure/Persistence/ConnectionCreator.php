<?php

namespace Mateus\ProtocolTracker\Infrastructure\Persistence;

use PDO;

class ConnectionCreator
{
    public static function createConnection() : PDO
    {
        $dbPath = __DIR__ . '/../../../data/db.sqlite'; 
        $connection = new PDO('sqlite:' . $dbPath);

        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connection;
    }
}