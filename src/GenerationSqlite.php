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
            return null;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! $result) {
            return null;
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
            return null;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! $result) {
            return null;
        }

        foreach ($result as $info) {
            $name = $info['name'];
            $type = $this->convertType($info['type']);

            $columns[] = new ColumnInfo($name, $type);
        }

        return $columns;

    }//end getColumnsInfo()


    protected function convertType(string $type): string
    {
        if (str_contains($type, 'INT')) {
            return PhpTypes::Integer->value;
        }

        if (str_contains($type, 'BOOLEAN')) {
            return PhpTypes::Bool->value;
        }

        if (str_contains($type, 'CHAR')
        || str_contains($type, 'TEXT')
        || str_contains($type, 'DATE')) {
            return PhpTypes::String->value;
        }

        if (str_contains($type, 'REAL')
        || str_contains($type, 'DOUBLE')
        || str_contains($type, 'FLOAT')
        || str_contains($type, 'NUMERIC')
        || str_contains($type, 'DECIMAL')
        ) {
            return PhpTypes::Float->value;
        }

        return PhpTypes::Mixed->value;

    }//end convertType()


}//end class
