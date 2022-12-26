<?php

declare(strict_types=1);

namespace SqlModels;

use \PDO;

abstract class Generation
{
    use FolderHandler;

    public const TYPE_SQLITE = 0;
    public const TYPE_MYSQL = 1;
    public const TYPE_POSTGRESQL = 2;

    protected PDO $connection;

    protected string $targetFolder;

    protected string $stubsFolder;

    protected string $dsn;

    /**
     * @return null|array<TableInfo>
     */
    abstract protected function getTablesInfo(): null|array;

    public function process(
        int $type,
        string $host,
        string $dbname,
        string $targetFolder,
        string $namespace,
        null|string $username = null,
        null|string $password = null
    ): bool|string
    {
        $result = $this->createFolder($targetFolder);

        if (is_string($result)) {
            return $result;
        }

        $this->targetFolder = $targetFolder . '/';

        $this->stubsFolder = $this->getStubsFolder();

        $this->dsn = $this->generateDsn($type, $host, $dbname);

        if (! $this->stubsFolder) {
            return 'Problem on getStubsFolder';
        }

        $this->connect($username, $password);

        $result = $this->generateFiles($namespace, $username, $password);

        if ($result) {
            return $result;
        }

        return $this->generateClasses($namespace);
    }

    protected function generateDsn(int $type, string $host, string $dbname): string
    {
        if ($type == self::TYPE_SQLITE) {
            return "sqlite:$host/$dbname";
        }
    }

    /**
     * @throws \PDOException
     */
    protected function connect(null|string $username = null, null|string $password = null): void
    {
        if (isset($this->connection)) {
            return;
        }

        $this->connection = new PDO($this->dsn, $username, $password);
    }

    protected function generateFiles(
        string $namespace,
        null|string $username = null,
        null|string $password = null
    ): null|string
    {
        $username = is_null($username) ? 'null' : $username;
        $password = is_null($password) ? 'null' : $password;

        $result = $this->generateFile(
            'Connection',
            ['{{namespace}}', '{{dsn}}', "'{{username}}'", "'{{password}}'"],
            [$namespace, $this->dsn, $username, $password]
        );

        if (! $result) {
            return 'Problem generating the connection file';
        }

        if (! $this->generateFile('Model', ['{{namespace}}'], [$namespace])) {
            return 'Problem generating the ModelBody file';
        }

        if (! $this->generateFile('SqlGenerator', ['{{namespace}}'], [$namespace])) {
            return 'Problem generating the SqlGenerator file';
        }

        return null;
    }

    /**
     * @param string $fileName model filename
     * @param array<string> $search keys to search
     * @param array<string> $replace words to replace
     */
    protected function generateFile(string $fileName, array $search, array $replace): bool
    {
        $newFileName = $this->targetFolder . $fileName . '.php';
        $fileName = $this->stubsFolder . $fileName;

        return $this->copyReplace($fileName, $newFileName, $search, $replace);
    }

    protected function generateClasses(string $namespace): bool|string
    {
        $content = $this->getClassmodelContent($namespace);

        if (! $content) {
            return 'Problem on generateClasses reading entity file';
        }

        $tablesInfo = $this->getTablesInfo();

        if (! $tablesInfo) {
            return 'Problem getting tables';
        }

        $this->generateModels($tablesInfo, $content);

        return true;
    }

    protected function getClassmodelContent(string $namespace): null|string
    {
        $content = file_get_contents($this->stubsFolder.'Class');

        if (! $content) {
            return null;
        }

        return str_replace('{{namespace}}', $namespace, $content);
    }

    /**
     * @param array<TableInfo> $tablesInfo
     * @param string $modelContent
     */
    protected function generateModels(array $tablesInfo, string $modelContent): void
    {
        foreach ($tablesInfo as $table) {
            $tableName = $table->name;

            $fileName = preg_replace("/[^\w_]/", '', $tableName);
            $fileName = $this->toPascalCase($fileName);

            [$columns, $properties] = $this->generateColumnsProperties($table->columns);

            $content = str_replace(
                ['{{class_name}}', '{{table_name}}', '{{columns}}', '{{properties}}'],
                [$fileName, $tableName, $columns, $properties],
                $modelContent
            );

            file_put_contents($this->targetFolder . $fileName . '.php', $content);
        }
    }

    /**
     * @param string $string
     * @return string
     */
    protected function toPascalCase(string $string): string
    {
        return str_replace('_', '', ucwords($string, '_'));
    }

    /**
     * @param array<string> $colNames
     * @return array<string, string>
     */
    protected function generateColumnsProperties(array $colNames): array
    {
        $columns = '';
        $properties = '';

        foreach ($colNames as $colName) {
            $columns .= strlen($columns) ? ',' : '[';
            $columns .= "\n\t\t'$colName'";

            $properties .= sprintf("\tpublic $%s;\n", $colName);
        }

        $columns .= "\n\t]";

        return [$columns, $properties];
    }
}