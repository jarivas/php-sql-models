<?php

declare(strict_types=1);

namespace SqlModels\Tests\Unit\Sqlite;

use PHPUnit\Framework\TestCase;
use SqlModels\Tests\Sqlite\Models\Album;

class ModelTest extends TestCase
{


    public function testGetOk(): void
    {
        $model = new Album();
        
        $albums = $model->get();

        $this->assertIsArray($albums);

        $this->assertInstanceOf(Album::class, $albums[0]);
    }//end testGetOk()


}//end class
