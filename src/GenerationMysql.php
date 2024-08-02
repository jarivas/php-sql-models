<?php

declare(strict_types=1);

namespace SqlModels;

use \PDO;

class GenerationMysql extends Generation
{


    /**
     * @return bool|array<string>
     */
    protected function getTableNames(): bool|array
    {
        $stmt = $this->connection->query('SHOW TABLES');

        if (!$stmt) {
            return false;
        }

        return $stmt->fetchAll(PDO::FETCH_COLUMN);

    }//end getTableNames()


    /**
     * @return bool|array<ColumnInfo>
     */
    protected function getColumnsInfo(string $tableName): bool|array
    {
        $columns = [];
        $stmt    = $this->connection->query("DESC `$tableName`");

        if (!$stmt) {
            return false;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! $result) {
            return false;
        }

        foreach ($result as $info) {
            $name  = $info['Field'];
            $typeName = $this->convertType($info['Type']);

            $columns[] = new ColumnInfo($name, $typeName, $info['Null'] != 'NO');
        }

        return $columns;

    }//end getColumnsInfo()


    protected function convertType(string $typeName): string
    {
        if (str_contains($typeName, 'int')) {
            return PhpTypes::Integer->value;
        }

        if (str_contains($typeName, 'char')
        || str_contains($typeName, 'text')
        || str_contains($typeName, 'date')
        || str_contains($typeName, 'time')
        || str_contains($typeName, 'year')
        || str_contains($typeName, 'enum')
        || str_contains($typeName, 'set')) {
            return PhpTypes::String->value;
        }

        if (str_contains($typeName, 'bool')
        || str_contains($typeName, 'bit')) {
            return PhpTypes::Bool->value;
        }

        if (str_contains($typeName, 'real')
        || str_contains($typeName, 'double')
        || str_contains($typeName, 'float')
        || str_contains($typeName, 'numeric')
        || str_contains($typeName, 'decimal')
        ) {
            return PhpTypes::Float->value;
        }

        return PhpTypes::Mixed->value;

    }//end convertType()


}//end class
