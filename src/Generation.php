<?php

declare(strict_types=1);

namespace SqlModels;

use \PDO;

abstract class Generation
{
    use FolderHandler;

    /**
     * @var PDO $connection used to talk to the DB
     */
    protected PDO $connection;

    /**
     * @var string $targetFolder holds the directory where the models will be generated
     */
    protected string $targetFolder;

    /**
     * @var string $stubsFolder holds the directory where the studs models are
     */
    protected string $stubsFolder;

    /**
     * @var string $dsn the connection string for PDO
     */
    protected string $dsn;


    /**
     * @return null|array<string>
     */
    abstract protected function getTableNames(): null|array;


    /**
     * @return null|array<string>
     */
    abstract protected function getColumnNames(string $tableName): null|array;


    public function process(
        Dbms $type,
        string $host,
        string $dbname,
        string $targetFolder,
        string $namespace,
        null|string $username=null,
        null|string $password=null
    ): bool|string
    {
        $dsn = $this->createFolder($targetFolder);

        if (is_string($dsn)) {
            return $dsn;
        }

        $this->targetFolder = $targetFolder.'/';

        $this->stubsFolder = $this->getStubsFolder();

        $this->dsn = $this->generateDsn($type, $host, $dbname);

        if (! $this->stubsFolder) {
            return 'Problem on getStubsFolder';
        }

        $this->connect($username, $password);

        $dsn = $this->generateFiles($namespace, $username, $password);

        if ($dsn) {
            return $dsn;
        }

        return $this->generateClasses($namespace);

    }//end process()


    protected function generateDsn(Dbms $type, string $host, string $dbname): string
    {
        $dsn = '';

        if ($type == Dbms::Sqlite) {
            $dsn = "sqlite:$host/$dbname";
        } else {
            $dsn = "{$type->value}:dbname=$dbname;host=$host";
        }

        return $dsn;

    }//end generateDsn()


    /**
     * @throws \PDOException
     */
    protected function connect(null|string $username=null, null|string $password=null): void
    {
        if (isset($this->connection)) {
            return;
        }

        $this->connection = new PDO($this->dsn, $username, $password);

    }//end connect()


    protected function generateFiles(
        string $namespace,
        null|string $username=null,
        null|string $password=null
    ): null|string
    {
        $username = is_null($username) ? 'null' : "'$username'";
        $password = is_null($password) ? 'null' : "'$password'";

        $dsn = $this->generateFile(
            'Connection',
            [
                '{{namespace}}',
                '{{dsn}}',
                "'{{username}}'",
                "'{{password}}'",
            ],
            [
                $namespace,
                $this->dsn,
                $username,
                $password,
            ]
        );

        if (! $dsn) {
            return 'Problem generating the connection file';
        }

        if (! $this->generateFile('Model', ['{{namespace}}'], [$namespace])) {
            return 'Problem generating the ModelBody file';
        }

        if (! $this->generateFile('SqlGenerator', ['{{namespace}}'], [$namespace])) {
            return 'Problem generating the SqlGenerator file';
        }

        return null;

    }//end generateFiles()


    /**
     * @param string $fileName model filename
     * @param array<string> $search keys to search
     * @param array<string> $replace words to replace
     */
    protected function generateFile(string $fileName, array $search, array $replace): bool
    {
        $newFileName = $this->targetFolder.$fileName.'.php';
        $fileName    = $this->stubsFolder.$fileName;

        return $this->copyReplace($fileName, $newFileName, $search, $replace);

    }//end generateFile()


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

    }//end generateClasses()


    /**
     * @return null|array<TableInfo>
     */
    protected function getTablesInfo(): null|array
    {
        $tableNames = $this->getTableNames();
        $columns    = [];
        $dsn        = [];

        if (is_null($tableNames)) {
            return null;
        }

        foreach ($tableNames as $tableName) {
            $columns = $this->getColumnNames($tableName);

            if ($columns) {
                $dsn[] = new TableInfo($tableName, $columns);
            }
        }

        return $dsn;

    }//end getTablesInfo()


    protected function getClassmodelContent(string $namespace): null|string
    {
        $content = file_get_contents($this->stubsFolder.'Class');

        if (! $content) {
            return null;
        }

        return str_replace('{{namespace}}', $namespace, $content);

    }//end getClassmodelContent()


    /**
     * @param array<TableInfo> $tablesInfo
     * @param string $modelContent
     */
    protected function generateModels(array $tablesInfo, string $modelContent): void
    {
        foreach ($tablesInfo as $table) {
            $tableName = $table->name;

            $fileName = preg_replace("/[^\w_]/", '', $tableName);

            if (is_string($fileName)) {
                $fileName = str_replace('_', '', ucwords($fileName, '_'));

                [
                    $columns,
                    $properties,
                ] = $this->generateColumnsProperties($table->columns);

                $content = str_replace(
                    [
                        '{{class_name}}',
                        '{{table_name}}',
                        '{{columns}}',
                        '{{properties}}',
                    ],
                    [
                        $fileName,
                        $tableName,
                        $columns,
                        $properties,
                    ],
                    $modelContent
                );

                file_put_contents($this->targetFolder.$fileName.'.php', $content);
            }//end if
        }//end foreach

    }//end generateModels()


    /**
     * @param array<string> $colNames
     * @return array<string>
     */
    protected function generateColumnsProperties(array $colNames): array
    {
        $columns    = '[';
        $properties = '';

        foreach ($colNames as $colName) {
            $columns .= "\n        '$colName',";

            $properties .= "    /**\n";
            $properties .= sprintf("     * @var mixed $%s\n", $colName);
            $properties .= "     */\n";
            $properties .= sprintf("    public mixed $%s;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps\n\n", $colName);
        }

        $columns .= "\n    ]";

        return [
            $columns,
            $properties,
        ];

    }//end generateColumnsProperties()


}//end class
