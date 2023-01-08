<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;

use Exception;
use PDO;

class Connection {

    /**
     * @var null|Connection $instance
     */
    private static null|Connection $instance = null;

    /**
     * @var string $dsn
     */
    private string $dsn = 'pgsql:host=postgres;dbname=Chinook';

    /**
     * @var PDO $db
     */
    private PDO $db;

    /**
     * @var null|string $username
     */
    private null|string $username = 'Chinook';

    /**
     * @var null|string $password
     */
    private null|string $password = 'Chinook';


    public static function getInstance() : Connection
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;

    }//end getInstance()


    private function __construct()
    {
        $this->db = new PDO($this->dsn, $this->username, $this->password);

    }//end __construct()


    /**
     * @param string $sql
     * @param bool|array<string, string> $params
     * @param string $className
     * @return bool|array<mixed> array of $className
     */
    public function get(string $sql, bool|array $params, string $className): bool|array
    {
        echo PHP_EOL.$sql.PHP_EOL.print_r($params, true).PHP_EOL;
        $stmt = $this->db->prepare($sql);

        try {
            if (! $stmt->execute($params)) {
                throw new Exception(implode(', ', $stmt->errorInfo()));
            }
        } catch (Exception $e) {
            throw new Exception($sql.' '.$e->getMessage());
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS, $className);

    }//end get()


    /**
     * @param string $sql
     * @param bool|array<string, string> $params
     */
    public function executeSql(string $sql, bool|array $params=null): void
    {
        echo PHP_EOL.$sql.PHP_EOL.print_r($params, true).PHP_EOL;
        $stmt = $this->db->prepare($sql);

        try {
            if (! $stmt->execute($params)) {
                throw new Exception(implode(', ', $stmt->errorInfo()));
            }
        } catch (Exception $e) {
            throw new Exception($sql.' '.$e->getMessage());
        }

    }//end executeSql()


    /**
     * @param string $sql
     */
    public function exec(string $sql): void
    {
        if (! $this->db->exec($sql)) {
            $error = $this->db->errorInfo();

            if ($error[0] != "00000") {
                throw new Exception($sql.' '.implode(', ', $error));
            }
        }

    }//end exec()


    public function lastInsertId(): bool|string
    {
        return $this->db->lastInsertId();

    }//end lastInsertId()


}//end class
