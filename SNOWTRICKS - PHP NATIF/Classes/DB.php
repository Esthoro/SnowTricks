<?php

namespace App;

use PDO;
use PDOException;

/**
 * Database params
 */
define('DBHOST', 'localhost');
define('DBNAME', 'snowtricks');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBPATH', 'mysql:host=' . DBHOST . ';dbname=' . DBNAME . ';charset=UTF8');

class DB
{
    protected static $dbh = null;
    public function __construct()
    {
        self::$dbh = self::connect();
    }

    /**
     * @return null
     */
    public static function connect()
    {
        if (is_null(self::$dbh)) {

            $attempts = 30;

            while ($attempts > 0) {
                try {
                    self::$dbh = new PDO(DBPATH, DBUSER, DBPASS,
                        [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;SET time_zone = "' . date('P') . '"']);
                    $attempts = 0;

                } catch (PDOException $e) {

                    $attempts--;
                }
            }
        }
        return self::$dbh;
    }

    /**
     * @param $sql
     * @param array $params
     * @return bool|object
     */
    public static function exec($sql, array $params = array())
    {
        if (self::$dbh = self::connect()) {
            try {
                $stmt = self::$dbh->prepare($sql);
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value);
                }
                $stmt->execute($params);
                return $stmt;
            } catch (PDOException $e) {
                return $e->getMessage();
            }
        }
        return false;
    }

    public static function lastInsertId() {
        if (self::$dbh) {
            return self::$dbh->lastInsertId();
        }
        return false;
    }
}
