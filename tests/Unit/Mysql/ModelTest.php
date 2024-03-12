<?php

declare(strict_types=1);

namespace SqlModels\Tests\Unit\Mysql;

use PHPUnit\Framework\TestCase;
use SqlModels\Tests\Mysql\Models\Album;
use SqlModels\Tests\Mysql\Models\Connection;

class ModelTest extends TestCase
{
    public function testSetValuesOk(): void
    {
        $id = (time() + rand(1, 100));

        $columnValues = [
            'Title' => __FUNCTION__.$id,
            'AlbumId' => $id,
            'ArtistId' => 1
        ];

        $album = new Album($columnValues);
        
        $data = $album->toArray();

        $this->assertEquals($columnValues, $data);
    }

    public function testGetOk(): void
    {
        $albums = Album::get([], 0, 1, ['Title']);

        $this->assertIsArray($albums);

        $model = $albums[0];

        $this->assertInstanceOf(Album::class, $model);

        $data = $model->toArray();

        $this->assertNotEmpty($data['Title']);
        $this->assertTrue(empty($data['AlbumId']));
        $this->assertTrue(empty($data['ArtistId']));

    }//end testGetOk()


    public function testFirst(): void
    {
        $albums = Album::get();

        $this->assertIsArray($albums);

        $album = Album::first(['Title' => $albums[0]->Title]);

        $this->assertNotFalse($album);

        $this->assertInstanceOf(Album::class, $album);

    }//end testFirst()


    public function testSelect(): void
    {
        $model = new Album();

        $model->select(['Title']);
        $model->hydrate(null);

        $this->assertNotFalse($model);
        $this->assertInstanceOf(Album::class, $model);

        $data = $model->toArray();

        $this->assertNotEmpty($data['Title']);
        $this->assertTrue(empty($data['AlbumId']));
        $this->assertTrue(empty($data['ArtistId']));

    }//end testSelect()


    public function testCreate(): Album
    {
        $album = new Album();
        $id = (time() + rand(1, 100));

        $album->Title    = __FUNCTION__.$id;
        $album->AlbumId  = $id;
        $album->ArtistId = 1;

        $album->save(null);

        $album2 = Album::first(['AlbumId' => $id]);

        $this->assertNotFalse($album2);
        $this->assertInstanceOf(Album::class, $album2);

        $this->assertSame(intval($album->AlbumId), $album2->AlbumId);

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
