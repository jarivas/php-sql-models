<?php

declare(strict_types=1);

namespace SqlModels\Tests\Unit\Mssql;

use PHPUnit\Framework\TestCase;
use SqlModels\DbConnectionInfo;
use SqlModels\GenerationMssql;
use SqlModels\Dbms;

class GenerationTest extends TestCase
{
    public const NAMESPACE = 'SqlModels\Tests\Mssql\Models';


    public function testGenerateOk(): void
    {
        $dir          = dirname(__DIR__, 2);
        $targetFolder = "$dir/Mssql/Models";

        $dbInfo = new DbConnectionInfo(Dbms::Mssql, $_ENV['MSSQL_HOST'], $_ENV['MYSQL_DATABASE'], 'SA', 'Password0!', 1433);

        $generation = new GenerationMssql($dbInfo, $targetFolder, self::NAMESPACE);

        $result = $generation->process();

        $this->assertTrue($result);

        $testFile = "$targetFolder/Connection.php";

        $this->assertFileExists($testFile);

        $content = file_get_contents($testFile);

        $this->assertNotEmpty($content);

        $this->assertStringContainsString(self::NAMESPACE, $content);

    }//end testGenerateOk()


}//end class
