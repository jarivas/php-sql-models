<?php

declare(strict_types=1);

namespace SqlModels;

use \PDO;

class GenerationSqlite extends Generation
{


    /**
     * @return bool|array<string>
     */
    protected function getTableNames(): bool|array
    {
        $tables = [];
        $stmt   = $this->connection->query('SELECT name FROM sqlite_master WHERE type = "table"');

        if (!$stmt) {
            return false;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! $result) {
            return false;
        }

        foreach ($result as $fielDesc) {
            $tables[] = $fielDesc['name'];
        }

        return $tables;

    }//end getTableNames()


    /**
     * @return bool|array<ColumnInfo>
     */
    protected function getColumnsInfo(string $_tableName): bool|array
    {
        $columns = [];
        $stmt    = $this->connection->query("PRAGMA table_info($_tableName);");

        if (!$stmt) {
            return false;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! $result) {
            return false;
        }

        foreach ($result as $info) {
            $name = $info['name'];
            $_type = $this->convertType($info['type']);

            $columns[] = new ColumnInfo($name, $_type, $info['notnull'] != '1');
        }

        return $columns;

    }//end getColumnsInfo()


    protected function convertType(string $_type): string
    {
        if (str_contains($_type, 'INT')) {
            return PhpTypes::Integer->value;
        }

        if (str_contains($_type, 'BOOLEAN')) {
            return PhpTypes::Bool->value;
        }

        if (str_contains($_type, 'CHAR')
        || str_contains($_type, 'TEXT')
        || str_contains($_type, 'DATE')) {
            return PhpTypes::String->value;
        }

        if (str_contains($_type, 'REAL')
        || str_contains($_type, 'DOUBLE')
        || str_contains($_type, 'FLOAT')
        || str_contains($_type, 'NUMERIC')
        || str_contains($_type, 'DECIMAL')
        ) {
            return PhpTypes::Float->value;
        }

        return PhpTypes::Mixed->value;

    }//end convertType()


}//end class
