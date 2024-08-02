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
     * @var string $stubsFolder holds the directory where the studs models are
     */
    protected string $stubsFolder;


    /**
     * @return bool|array<string>
     */
    abstract protected function getTableNames(): bool|array;


    /**
     * @return bool|array<ColumnInfo>
     */
    abstract protected function getColumnsInfo(string $tableName): bool|array;


    public function __construct(
        private DbConnectionInfo $dbInfo,
        private string $targetFolder,
        private string $namespace
        ) {

    }//end __construct()


    public function process(): bool|string
    {
        $result = $this->createFolder($this->targetFolder);

        if (is_string($result)) {
            return $result;
        }

        $this->targetFolder .= '/';

        $this->stubsFolder = $this->getStubsFolder();

        if (! $this->stubsFolder) {
            return 'Problem on getStubsFolder';
        }

        $this->connect();

        $result = $this->generateFiles();

        if ($result) {
            return $result;
        }

        return $this->generateClasses();

    }//end process()


    /**
     * @throws \PDOException
     */
    protected function connect(): void
    {
        if (isset($this->connection)) {
            return;
        }

        if (is_string($this->dbInfo->sshTunnel)) {
            shell_exec($this->dbInfo->sshTunnel);
        }

        $this->connection = new PDO($this->dbInfo->dsn, $this->dbInfo->dbUsername, $this->dbInfo->dbPassword);

    }//end connect()


    protected function generateFiles(): bool|string
    {
        $username  = is_null($this->dbInfo->dbUsername) ? 'null' : "'{$this->dbInfo->dbUsername}'";
        $password  = is_null($this->dbInfo->dbPassword) ? 'null' : "'{$this->dbInfo->dbPassword}'";
        $sshTunnel = is_null($this->dbInfo->sshTunnel) ? 'null' : "'{$this->dbInfo->sshTunnel}'";

        $result = $this->generateFile(
            'Connection',
            [
                '{{namespace}}',
                '{{dsn}}',
                '{{sshTunnel}}',
                '{{username}}',
                '{{password}}',
            ],
            [
                $this->namespace,
                $this->dbInfo->dsn,
                $sshTunnel,
                $username,
                $password,
            ]
        );

        if (! $result) {
            return 'Problem generating the connection file';
        }

        if (! $this->generateFile('Model', ['{{namespace}}'], [$this->namespace])) {
            return 'Problem generating the ModelBody file';
        }

        if (! $this->generateFile('SqlGenerator', ['{{namespace}}'], [$this->namespace])) {
            return 'Problem generating the SqlGenerator file';
        }

        if (! $this->generateEnum($this->namespace)) {
            return 'Problem generating the enum files';
        }

        return false;

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


    protected function generateClasses(): bool|string
    {
        $content = $this->getClassmodelContent($this->namespace);

        if (is_bool($content)) {
            return 'Problem on generateClasses reading entity file';
        }

        $tablesInfo = $this->getTablesInfo();

        if (is_bool($tablesInfo)) {
            return 'Problem getting tables';
        }

        $this->generateModels($tablesInfo, $content);

        return true;

    }//end generateClasses()


    /**
     * @return bool|array<TableInfo>
     */
    protected function getTablesInfo(): bool|array
    {
        $tableNames = $this->getTableNames();
        $columns    = [];
        $result     = [];

        if (is_bool($tableNames)) {
            return false;
        }

        foreach ($tableNames as $tableName) {
            $columns = $this->getColumnsInfo($tableName);

            if (is_array($columns)) {
                $result[] = new TableInfo($tableName, $columns);
            }
        }

        return $result;

    }//end getTablesInfo()


    /**
     * @param string $namespace
     * @return bool|string
     */
    protected function getClassmodelContent(string $namespace): bool|string
    {
        $content = file_get_contents($this->stubsFolder.'Class');

        if (! $content) {
            return false;
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
                        '{{type}}',
                    ],
                    [
                        $fileName,
                        $tableName,
                        $columns,
                        $properties,
                        ucfirst($this->dbInfo->type->value),
                    ],
                    $modelContent
                );

                file_put_contents($this->targetFolder.$fileName.'.php', $content);
            }//end if
        }//end foreach

    }//end generateModels()


    /**
     * @param array<ColumnInfo> $columnsInfo
     * @return array<string>
     */
    protected function generateColumnsProperties(array $columnsInfo): array
    {
        $columns    = '[';
        $properties = '';
        $text       = '';

        foreach ($columnsInfo as $info) {
            $nullable = $info->nullable ? '?' : '';
            $columns .= "\n        '{$info->name}',";
            $text     = $nullable.$info->typeName.' $'.$info->name;

            $properties .= "    /**\n";
            $properties .= "     * @var $text\n";
            $properties .= "     */\n";
            $properties .= "    public $text;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps\n\n";
        }

        $columns .= "\n    ]";

        return [
            $columns,
            $properties,
        ];

    }//end generateColumnsProperties()


    /**
     * @param string $namespace
     * @return bool
     */
    protected function generateEnum(string $namespace): bool
    {
        $files     = [
            'Dbms.php',
            'Join.php',
            'Logger.php',
        ];
        $fileName  = '';
        $i         = 0;
        $max       = count($files);
        $next      = false;
        $namespace = "namespace $namespace;";

        do {
            $fileName    = $files[$i];
            $newFileName = $this->targetFolder.$fileName;
            $fileName    = dirname($this->stubsFolder, 1).'/'.$fileName;

            $next = $this->copyReplace($fileName, $newFileName, ['namespace SqlModels;'], [$namespace]);
            ++$i;
        } while ($i < $max && $next);

        return $next;

    }//end generateEnum()


}//end class
