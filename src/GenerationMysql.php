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
    protected function getColumnsInfo(string $_tableName): bool|array
    {
        $columns = [];
        $stmt    = $this->connection->query("DESC `$_tableName`");

        if (!$stmt) {
            return false;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! $result) {
            return false;
        }

        foreach ($result as $info) {
            $name = $info['Field'];
            $_type = $this->convertType($info['Type']);

            $columns[] = new ColumnInfo($name, $_type, $info['Null'] != 'NO');
        }

        return $columns;

    }//end getColumnsInfo()


    protected function convertType(string $_type): string
    {
        if (str_contains($_type, 'int')) {
            return PhpTypes::Integer->value;
        }

        if (str_contains($_type, 'char')
        || str_contains($_type, 'text')
        || str_contains($_type, 'date')
        || str_contains($_type, 'time')
        || str_contains($_type, 'year')
        || str_contains($_type, 'enum')
        || str_contains($_type, 'set')) {
            return PhpTypes::String->value;
        }

        if (str_contains($_type, 'bool')
        || str_contains($_type, 'bit')) {
            return PhpTypes::Bool->value;
        }

        if (str_contains($_type, 'real')
        || str_contains($_type, 'double')
        || str_contains($_type, 'float')
        || str_contains($_type, 'numeric')
        || str_contains($_type, 'decimal')
        ) {
            return PhpTypes::Float->value;
        }

        return PhpTypes::Mixed->value;

    }//end convertType()


}//end class
