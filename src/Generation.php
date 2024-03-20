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
     * @var Dbms $_type
     */
    protected Dbms $_type;

    /**
     * @var string $dbName
     */
    protected string $dbName;


    /**
     * @return bool|array<string>
     */
    abstract protected function getTableNames(): bool|array;


    /**
     * @return bool|array<ColumnInfo>
     */
    abstract protected function getColumnsInfo(string $_tableName): bool|array;


    public function process(
        Dbms $_type,
        string $host,
        string $dbName,
        string $targetFolder,
        string $namespace,
        null|string $username=null,
        null|string $password=null
    ): bool|string
    {
        $this->_type   = $_type;
        $this->dbName = $dbName;

        $result = $this->createFolder($targetFolder);

        if (is_string($result)) {
            return $result;
        }

        $this->targetFolder = $targetFolder.'/';

        $this->stubsFolder = $this->getStubsFolder();

        $this->dsn = $this->generateDsn($host);

        if (! $this->stubsFolder) {
            return 'Problem on getStubsFolder';
        }

        $this->connect($username, $password);

        $result = $this->generateFiles($namespace, $username, $password);

        if ($result) {
            return $result;
        }

        return $this->generateClasses($namespace);

    }//end process()


    protected function generateDsn(string $host): string
    {
        $dsn = '';

        if ($this->_type == Dbms::Sqlite) {
            $dsn = "sqlite:$host/{$this->dbName}";
        } else {
            $dsn = "{$this->_type->value}:host=$host;dbname={$this->dbName}";
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
    ): bool|string
    {
        $username = is_null($username) ? 'null' : "'$username'";
        $password = is_null($password) ? 'null' : "'$password'";

        $result = $this->generateFile(
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

        if (! $result) {
            return 'Problem generating the connection file';
        }

        if (! $this->generateFile('Model', ['{{namespace}}'], [$namespace])) {
            return 'Problem generating the ModelBody file';
        }

        if (! $this->generateFile('SqlGenerator', ['{{namespace}}'], [$namespace])) {
            return 'Problem generating the SqlGenerator file';
        }

        if (! $this->generateEnum($namespace)) {
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
     * @return bool|array<TableInfo>
     */
    protected function getTablesInfo(): bool|array
    {
        $tableNames = $this->getTableNames();
        $columns    = [];
        $result     = [];

        if (is_null($tableNames)) {
            return false;
        }

        foreach ($tableNames as $_tableName) {
            $columns = $this->getColumnsInfo($_tableName);

            if ($columns) {
                $result[] = new TableInfo($_tableName, $columns);
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
            $_tableName = $table->name;

            $fileName = preg_replace("/[^\w_]/", '', $_tableName);

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
                        $_tableName,
                        $columns,
                        $properties,
                        ucfirst($this->_type->value),
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
            $text     = $nullable.$info->_type.' $'.$info->name;

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
        $files = ['Dbms.php', 'Join.php', 'Logger.php'];
        $fileName = '';
        $i = 0;
        $max = count($files);
        $next = false;
        $namespace = "namespace $namespace;";
        
        do {
            $fileName = $files[$i];
            $newFileName = $this->targetFolder.$fileName;
            $fileName = dirname($this->stubsFolder, 1).'/'.$fileName;
                
            $next = $this->copyReplace($fileName, $newFileName, ['namespace SqlModels;'], [$namespace]);
            ++$i;
        }while($i < $max && $next);

        return $next;

    }//end generateEnum()


}//end class
