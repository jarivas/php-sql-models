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
    protected function getColumnsInfo(string $tableName): bool|array
    {
        $columns = [];
        $stmt    = $this->connection->query("SELECT column_name, data_type, is_nullable FROM information_schema.columns WHERE table_name = '$tableName';");

        if (!$stmt) {
            return false;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! $result) {
            return false;
        }

        foreach ($result as $info) {
            $name = $info['column_name'];
            $typeName = $this->convertType($info['data_type']);

            $columns[] = new ColumnInfo($name, $typeName, $info['is_nullable'] != 'NO');
        }

        return $columns;

    }//end getColumnsInfo()


    protected function convertType(string $typeName): string
    {
        if (str_contains($typeName, 'int')
        || str_contains($typeName, 'serial')
        ) {
            return PhpTypes::Integer->value;
        }

        if (str_contains($typeName, 'char')
        || str_contains($typeName, 'text')
        || str_contains($typeName, 'date')
        || str_contains($typeName, 'time')
        || str_contains($typeName, 'interval')
        || str_contains($typeName, 'enum')
        ) {
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
        || str_contains($typeName, 'money')
        ) {
            return PhpTypes::Float->value;
        }

        return PhpTypes::Mixed->value;

    }//end convertType()


}//end class
