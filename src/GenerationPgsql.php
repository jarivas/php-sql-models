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
            $type = $this->convertType($info['data_type']);

            $columns[] = new ColumnInfo($name, $type, $info['is_nullable'] != 'NO');
        }

        return $columns;

    }//end getColumnsInfo()


    protected function convertType(string $type): string
    {
        if (str_contains($type, 'int')
        || str_contains($type, 'serial')
        ) {
            return PhpTypes::Integer->value;
        }

        if (str_contains($type, 'char')
        || str_contains($type, 'text')
        || str_contains($type, 'date')
        || str_contains($type, 'time')
        || str_contains($type, 'interval')
        || str_contains($type, 'enum')
        ) {
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
        || str_contains($type, 'money')
        ) {
            return PhpTypes::Float->value;
        }

        return PhpTypes::Mixed->value;

    }//end convertType()


}//end class
