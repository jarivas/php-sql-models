<?php

declare(strict_types=1);

namespace SqlModels\Tests\Unit\Mysql;

use PHPUnit\Framework\TestCase;
use SqlModels\DbConnectionInfo;
use SqlModels\GenerationMysql;
use SqlModels\Dbms;

class GenerationTest extends TestCase
{
    public const NAMESPACE = 'SqlModels\Tests\Mysql\Models';


    public function testGenerateOk(): void
    {
        $dir          = dirname(__DIR__, 2);
        $targetFolder = "$dir/Mysql/Models";
        $dbInfo       = new DbConnectionInfo(Dbms::Mysql, $_ENV['MYSQL_HOST'], $_ENV['MYSQL_DATABASE'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD']);
        $generation   = new GenerationMysql($dbInfo, $targetFolder, self::NAMESPACE);

        $result = $generation->process();

        $this->assertTrue($result);

        $testFile = "$targetFolder/Connection.php";

        $this->assertFileExists($testFile);

        $content = file_get_contents($testFile);

        $this->assertNotEmpty($content);

        $this->assertStringContainsString(self::NAMESPACE, $content);

    }//end testGenerateOk()


}//end class
