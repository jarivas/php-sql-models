<?php

declare(strict_types=1);

namespace SqlModels;

use \PDO;

class GenerationPgsql extends Generation
{


    /**
     * @return bool|array<string>
     */
    protected function getTableNames(): bool|array
    {
        $stmt = $this->connection->query(
            "SELECT table_name FROM information_schema.tables WHERE table_schema='public' AND table_type='BASE TABLE';"
        );

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
        $stmt    = $this->connection->query("SELECT column_name, data_type, is_nullable FROM information_schema.columns WHERE table_name = '$_tableName';");

        if (!$stmt) {
            return false;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! $result) {
            return false;
        }

        foreach ($result as $info) {
            $name = $info['column_name'];
            $_type = $this->convertType($info['data_type']);

            $columns[] = new ColumnInfo($name, $_type, $info['is_nullable'] != 'NO');
        }

        return $columns;

    }//end getColumnsInfo()


    protected function convertType(string $_type): string
    {
        if (str_contains($_type, 'int')
        || str_contains($_type, 'serial')
        ) {
            return PhpTypes::Integer->value;
        }

        if (str_contains($_type, 'char')
        || str_contains($_type, 'text')
        || str_contains($_type, 'date')
        || str_contains($_type, 'time')
        || str_contains($_type, 'interval')
        || str_contains($_type, 'enum')
        ) {
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
        || str_contains($_type, 'money')
        ) {
            return PhpTypes::Float->value;
        }

        return PhpTypes::Mixed->value;

    }//end convertType()


}//end class
