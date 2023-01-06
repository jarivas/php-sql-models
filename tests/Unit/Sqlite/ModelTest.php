<?php

declare(strict_types=1);

namespace SqlModels\Tests\Unit\Sqlite;

use PHPUnit\Framework\TestCase;
use SqlModels\Tests\Sqlite\Models\Album;
use SqlModels\Tests\Sqlite\Models\Connection;


class ModelTest extends TestCase
{


    public function testGetOk(): void
    {
        $model = new Album();

        $albums = $model->get();

        $this->assertIsArray($albums);

        $this->assertInstanceOf(Album::class, $albums[0]);

    }//end testGetOk()


    public function testGetOne(): void
    {
        $model  = new Album();
        $albums = $model->get();

        $this->assertIsArray($albums);

        $model->where('Title', '=', $albums[0]->Title);

        $album = $model->getOne();

        $this->assertNotFalse($album);

        $this->assertInstanceOf(Album::class, $album);

        $this->assertSame($albums[0]->Title, $album->Title);

    }//end testGetOne()


    public function testFirst(): void
    {
        $model  = new Album();
        $albums = $model->get();

        $this->assertIsArray($albums);

        $album = Album::first(['Title' => $albums[0]->Title]);

        $this->assertNotFalse($album);

        $this->assertInstanceOf(Album::class, $album);

    }//end testFirst()


    public function testSelect(): void
    {
        $model = new Album();

        $model->select(['Title', 'AlbumId']);
        $album = $model->getOne();

        $this->assertNotFalse($album);
        $this->assertInstanceOf(Album::class, $album);

        $this->assertNotEmpty($album->Title);
        $this->assertNotEmpty($album->AlbumId);

    }//end testSelect()


    public function testCreate(): Album
    {
        $model  = new Album();
        $albums = $model->get();

        $this->assertIsArray($albums);

        $album = Album::first(['Title' => $albums[0]->Title]);

        $this->assertNotFalse($album);
        $this->assertInstanceOf(Album::class, $album);

        unset($album->AlbumId);

        $album->Title .= microtime();

        $album->save('AlbumId');

        $this->assertNotEmpty($album->AlbumId);

        return $album;

    }//end testCreate()


    public function testUpdate(): void
    {
        Connection::getInstance()->executeSql('DELETE FROM album WHERE Title = :Title', ['Title' => __FUNCTION__]);

        $album = $this->testCreate();

        $album->Title = __FUNCTION__;

        $album->save('AlbumId');

        $album2 = Album::first(['Title' => __FUNCTION__]);

        $this->assertNotFalse($album2);
        $this->assertInstanceOf(Album::class, $album2);

        $this->assertSame(intval($album->AlbumId), $album2->AlbumId);

    }//end testUpdate()


    public function testDelete(): void
    {
        Connection::getInstance()->executeSql('DELETE FROM album WHERE Title = :Title', ['Title' => __FUNCTION__]);

        $album = $this->testCreate();

        $album->Title = __FUNCTION__;

        $album->save('AlbumId');

        $album->delete('AlbumId');

        $album = Album::first(['Title' => __FUNCTION__]);

        $this->assertFalse($album);

    }//end testDelete()


    public function testToJson(): void
    {
        $album        = $this->testCreate();
        $album->Title = __FUNCTION__;

        $json  = json_encode($album);
        $array = json_decode($json, true);

        $this->assertIsArray($array);
        $this->assertArrayHasKey('Title', $array);
        $this->assertSame(__FUNCTION__, $array['Title']);

    }//end testToJson()


}//end class
