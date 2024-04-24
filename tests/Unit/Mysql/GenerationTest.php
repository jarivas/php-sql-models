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
        $dbInfo       = new DbConnectionInfo(
            Dbms::Mysql,
            '127.0.0.1',
            $_ENV['MYSQL_DATABASE'],
            $_ENV['MYSQL_USER'],
            $_ENV['MYSQL_PASSWORD'],
            3306,
            $_ENV['MYSQL_HOST'],
            $_ENV['MYSQL_USER'],
            $_ENV['MYSQL_PASSWORD'],
            2222,
        );
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
