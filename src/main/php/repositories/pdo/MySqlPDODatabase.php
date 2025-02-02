<?php

declare(strict_types=1);

namespace repositories\pdo;


/**
 * Connects to a MySQL database via PDO.
 *
 * @link        https://www.php.net/manual/pt_BR/book.pdo.php
 * @link        https://www.mysql.com/
 */
class MySqlPDODatabase extends PDODatabase
{

    private static $instance = null;
    private function __construct() {}

    public static function shared()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    //-------------------------------------------------------------------------
    //        Methods
    //-------------------------------------------------------------------------
    /**
     * {@inheritdoc}
     * @Override
     */
    protected function getInstance()
    {
        $pdo = new \PDO(
            "mysql:dbname=" . $this->getDatabase() . ";
            host=" . $this->getHost() . ";
            charset=" . $this->getCharset(),
            $this->getUsername(),
            $this->getPassword()
        );

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

        return $pdo;
    }
}
