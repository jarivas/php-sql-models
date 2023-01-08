<?php

declare(strict_types=1);

namespace SqlModels\Tests\Unit\Pgsql;

use PHPUnit\Framework\TestCase;
use SqlModels\GenerationPgsql;
use SqlModels\Dbms;

class GenerationTest extends TestCase
{
    public const NAMESPACE = 'SqlModels\Tests\Pgsql\Models';


    public function testGeneratePOk(): void
    {
        $dir          = dirname(__DIR__, 2);
        $targetFolder = "$dir/Pgsql/Models";
        $generation   = new GenerationPgsql();

        $result = $generation->process(
            Dbms::Pgsql,
            $_ENV['PGSQL_HOST'],
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
