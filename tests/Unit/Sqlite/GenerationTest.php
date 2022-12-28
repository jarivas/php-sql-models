<?php

declare(strict_types=1);

namespace SqlModels\Tests\Unit\Sqlite;

use PHPUnit\Framework\TestCase;
use SqlModels\GenerationSqlite;

class GenerationTest extends TestCase
{
    public const NAMESPACE = 'SqlModels\Tests\Sqlite\Models';


    public function testGenerateOk(): void
    {
        $dir          = dirname(__DIR__, 2);
        $targetFolder = "$dir/Sqlite/Models";
        $generation   = new GenerationSqlite();

        $result = $generation->process($generation::TYPE_SQLITE, "$dir/Sqlite", 'chinook.db', $targetFolder, self::NAMESPACE);

        $this->assertTrue($result);

        $testFile = "$targetFolder/Connection.php";

        $this->assertFileExists($testFile);

        $content = file_get_contents($testFile);

        $this->assertNotEmpty($content);

        $this->assertStringContainsString(self::NAMESPACE, $content);

    }//end testGenerateOk()


}//end class
