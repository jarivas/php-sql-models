<?php

declare(strict_types=1);

namespace SqlModels;

use \PDO;

class GenerationSqlite extends Generation
{
    /**
     * @return null|array<TableInfo>
     */
    protected function getTablesInfo(): null|array
    {
        $tableNames = $this->getTableNames();
        $columns = [];
        $result = [];

        if (is_null($tableNames)) {
            return null;
        }

        foreach($tableNames as $tableName) {
            $columns = $this->getColumnNames($tableName);

            if ($columns) {
                $result[] = new TableInfo($tableName, $columns);
            }
        }

        return $result;
    }

    protected function getTableNames(): null|array
    {
        $tables = [];
        $stmt = $this->connection->query('SELECT name FROM sqlite_master WHERE type = "table"');

        if (!$stmt) {
            return null;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! $result) {
            return null;
        }

        foreach($result as $fielDesc) {
            $tables[] = $fielDesc['name'];
        }

        return $tables;
    }

    protected function getColumnNames(string $tableName): null|array
    {
        $columns = [];
        $stmt = $this->connection->query("PRAGMA table_info($tableName);");

        if (!$stmt) {
            return null;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! $result) {
            return null;
        }

        foreach($result as $fielDesc) {
            $columns[] = $fielDesc['name'];
        }

        return $columns;
    }
}