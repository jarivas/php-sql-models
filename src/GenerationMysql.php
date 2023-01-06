<?php

declare(strict_types=1);

namespace SqlModels;

use \PDO;

class GenerationMysql extends Generation
{


    /**
     * @return null|array<string>
     */
    protected function getTableNames(): null|array
    {
        $tables = [];
        $stmt   = $this->connection->query('SHOW TABLES');

        if (!$stmt) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }//end getTableNames()


    /**
     * @return null|array<string>
     */
    protected function getColumnNames(string $tableName): null|array
    {
        $columns = [];
        $stmt    = $this->connection->query("DESC `$tableName`");

        if (!$stmt) {
            return null;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! $result) {
            return null;
        }

        foreach ($result as $fielDesc) {
            $columns[] = $fielDesc['Field'];
        }

        return $columns;

    }//end getColumnNames()


}//end class
