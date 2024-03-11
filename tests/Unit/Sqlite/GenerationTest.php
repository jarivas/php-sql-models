<?php

declare(strict_types=1);

namespace SqlModels\Tests\Unit\Sqlite;

use PHPUnit\Framework\TestCase;
use SqlModels\GenerationSqlite;
use SqlModels\Dbms;

class GenerationTest extends TestCase
{
    public const NAMESPACE = 'SqlModels\Tests\Sqlite\Models';


    public function testGenerateOk(): void
    {
        $appDir       = dirname(__DIR__, 3);
        $testDir      = dirname(__DIR__, 2);
        $host         = "$appDir/db/Sqlite";
        $dbName       = 'chinook.db';
        $targetFolder = "$testDir/Sqlite/Models";
        $generation   = new GenerationSqlite();

        $result = $generation->process(Dbms::Sqlite, $host, $dbName, $targetFolder, self::NAMESPACE);

        $this->assertTrue($result);

        $testFile = "$targetFolder/Connection.php";

        $this->assertFileExists($testFile);

        $content = file_get_contents($testFile);

        $this->assertNotEmpty($content);

        $this->assertStringContainsString(self::NAMESPACE, $content);

    }//end testGenerateSqliteOk()


}//end class
