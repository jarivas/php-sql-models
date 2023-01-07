<?php

declare(strict_types=1);

namespace SqlModels\Tests\Unit\Mysql;

use PHPUnit\Framework\TestCase;
use SqlModels\GenerationMysql;
use SqlModels\Dbms;

class GenerationTest extends TestCase
{
    public const NAMESPACE = 'SqlModels\Tests\Mysql\Models';


    public function testGenerateMyOk(): void
    {
        $dir          = dirname(__DIR__, 2);
        $targetFolder = "$dir/Mysql/Models";
        $generation   = new GenerationMysql();

        $result = $generation->process(
            Dbms::Mysql,
            $_ENV['MYSQL_HOST'],
            $_ENV['MYSQL_DATABASE'],
            $targetFolder,
            self::NAMESPACE,
            $_ENV['MYSQL_USER'],
            $_ENV['MYSQL_PASSWORD'],
        );

        $this->assertTrue($result);

        $testFile = "$targetFolder/Connection.php";

        $this->assertFileExists($testFile);

        $content = file_get_contents($testFile);

        $this->assertNotEmpty($content);

        $this->assertStringContainsString(self::NAMESPACE, $content);

    }//end testGenerateOk()


}//end class
