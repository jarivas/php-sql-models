<?php

declare(strict_types=1);

namespace SqlModels;

class DbConnectionInfo
{

    public string $dsn;

    public null|string $sshTunnel = null;


    public function __construct(
        public Dbms $type,
        public string $dbHost,
        public string $dbName,
        public null|string $dbUsername=null,
        public null|string $dbPassword=null,
        public int $dbPort=3306,
        public null|string $sshHost=null,
        public null|string $sshUsername=null,
        public null|string $sshPassword=null,
        public int $sshPort=22,
        )
    {
        $this->setSshTunnel();
        $this->setDsn();

    }//end __construct()


    private function setDsn(): void
    {
        $this->dsn = ($this->type == Dbms::Sqlite) ? "sqlite:{$this->dbHost}/{$this->dbName}" : "{$this->type->value}:host={$this->dbHost};dbname={$this->dbName};port={$this->dbPort}";

    }//end setDsn()


    private function setSshTunnel(): void
    {
        if (is_null($this->sshHost)) {
            return;
        }

        ++$this->dbPort;

        $this->sshTunnel = "sshpass -p {$this->sshPassword} ssh -fN -L {$this->dbPort}:{$this->sshHost}:{$this->sshPort} {$this->sshUsername}@{$this->sshHost}";

    }//end setSshTunnel()


}//end class
