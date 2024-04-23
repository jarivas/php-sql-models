<?php

declare(strict_types=1);

namespace SqlModels;

class DbConnectionInfo
{

    private string $dsn;


    public function __construct(
        public Dbms $type,
        public string $host,
        public string $dbName,
        public null|string $username=null,
        public null|string $password=null
        )
    {

    }//end __construct()


    public function generateDsn(): string
    {
        if (!empty($this->dsn)) {
            return $this->dsn;
        }

        $this->dsn = ($this->type == Dbms::Sqlite) ? "sqlite:{$this->host}/{$this->dbName}" : "{$this->type->value}:host={$this->host};dbname={$this->dbName}";

        return $this->dsn;

    }//end generateDsn()


}//end class
