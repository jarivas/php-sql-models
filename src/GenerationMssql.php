<?php

declare(strict_types=1);

namespace SqlModels;

use \PDO;

class GenerationMssql extends Generation
{


    /**
     * @return bool|array<string>
     */
    protected function getTableNames(): bool|array
    {
        $dbName = $this->dbInfo->dbName;
        $stmt = $this->connection->query("SELECT TABLE_NAME FROM $dbName.INFORMATION_SCHEMA.TABLES;");

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
        $dbName = $this->dbInfo->dbName;
        $sql = "SELECT COLUMN_NAME, IS_NULLABLE, DATA_TYPE FROM $dbName.INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName' ORDER By ordinal_position;";
        $columns = [];

        $stmt    = $this->connection->query($sql);

        if (!$stmt) {
            return false;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! $result) {
            return false;
        }

        foreach ($result as $info) {
            $name  = $info['COLUMN_NAME'];
            $typeName = $this->convertType($info['DATA_TYPE']);

            $columns[] = new ColumnInfo($name, $typeName, $info['IS_NULLABLE'] != 'NO');
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
        || str_contains($typeName, 'binay')
        || str_contains($typeName, 'image')) {
            return PhpTypes::String->value;
        }

        if (str_contains($typeName, 'bit')) {
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
