<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;

/**
 * @method static bool|Album first(array<string, string> $columnValues)
 * @method bool|array<Album> get()
 * @method bool|Album getOne()
 */
class Album extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'album';

    /**
     * @var array<string> $columns
     */
    protected static array $columns = [
        'AlbumId',
        'ArtistId',
        'Title',
    ];

    /**
     * @var int $AlbumId
     */
    public int $AlbumId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $ArtistId
     */
    public int $ArtistId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $Title
     */
    public string $Title;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
