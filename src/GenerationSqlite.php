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
    protected function getColumnsInfo(string $tableName): bool|array
    {
        $columns = [];
        $stmt    = $this->connection->query("PRAGMA table_info($tableName);");

        if (!$stmt) {
            return false;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! $result) {
            return false;
        }

        foreach ($result as $info) {
            $name = $info['name'];
            $typeName = $this->convertType($info['type']);

            $columns[] = new ColumnInfo($name, $typeName, $info['notnull'] != '1');
        }

        return $columns;

    }//end getColumnsInfo()


    protected function convertType(string $typeName): string
    {
        if (str_contains($typeName, 'INT')) {
            return PhpTypes::Integer->value;
        }

        if (str_contains($typeName, 'BOOLEAN')) {
            return PhpTypes::Bool->value;
        }

        if (str_contains($typeName, 'CHAR')
        || str_contains($typeName, 'TEXT')
        || str_contains($typeName, 'DATE')) {
            return PhpTypes::String->value;
        }

        if (str_contains($typeName, 'REAL')
        || str_contains($typeName, 'DOUBLE')
        || str_contains($typeName, 'FLOAT')
        || str_contains($typeName, 'NUMERIC')
        || str_contains($typeName, 'DECIMAL')
        ) {
            return PhpTypes::Float->value;
        }

        return PhpTypes::Mixed->value;

    }//end convertType()


}//end class
