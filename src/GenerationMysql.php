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
            $name = $info['Field'];
            $type = $this->convertType($info['Type']);

            $columns[] = new ColumnInfo($name, $type, $info['Null'] != 'NO');
        }

        return $columns;

    }//end getColumnsInfo()


    protected function convertType(string $type): string
    {
        if (str_contains($type, 'int')) {
            return PhpTypes::Integer->value;
        }

        if (str_contains($type, 'char')
        || str_contains($type, 'text')
        || str_contains($type, 'date')
        || str_contains($type, 'time')
        || str_contains($type, 'year')
        || str_contains($type, 'enum')
        || str_contains($type, 'set')) {
            return PhpTypes::String->value;
        }

        if (str_contains($type, 'bool')
        || str_contains($type, 'bit')) {
            return PhpTypes::Bool->value;
        }

        if (str_contains($type, 'real')
        || str_contains($type, 'double')
        || str_contains($type, 'float')
        || str_contains($type, 'numeric')
        || str_contains($type, 'decimal')
        ) {
            return PhpTypes::Float->value;
        }

        return PhpTypes::Mixed->value;

    }//end convertType()


}//end class
