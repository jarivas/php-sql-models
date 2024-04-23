<?php

declare(strict_types=1);

namespace SqlModels\Tests\Unit\Pgsql;

use PHPUnit\Framework\TestCase;
use SqlModels\DbConnectionInfo;
use SqlModels\GenerationPgsql;
use SqlModels\Dbms;

class GenerationTest extends TestCase
{
    public const NAMESPACE = 'SqlModels\Tests\Pgsql\Models';


    public function testGenerateOk(): void
    {
        $dir          = dirname(__DIR__, 2);
        $targetFolder = "$dir/Pgsql/Models";
        $dbInfo       = new DbConnectionInfo(Dbms::Pgsql, $_ENV['PGSQL_HOST'], $_ENV['MYSQL_DATABASE'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD']);
        $generation   = new GenerationPgsql($dbInfo, $targetFolder, self::NAMESPACE);

        $result = $generation->process();

        $this->assertTrue($result);

        $testFile = "$targetFolder/Connection.php";

        $this->assertFileExists($testFile);

        $content = file_get_contents($testFile);

        $this->assertNotEmpty($content);

        $this->assertStringContainsString(self::NAMESPACE, $content);

    }//end testGenerateOk()


}//end class
