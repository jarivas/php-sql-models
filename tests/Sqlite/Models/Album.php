<?php
declare(strict_types=1);

namespace SqlModels\Tests\Sqlite\Models;

/**
 * @method static bool|Album first(array<string, mixed> $columnValues)
 * @method static bool|Album[] get(array<string, mixed> $columnValues = [], int $offset = 0, int $limit = 100, array $columns = []): bool|array
 */
class Album extends Model
{

    /**
     * @var string $tableName
     */
    public static string $tableName = 'Album';

    /**
     * @var string $primaryKey
     */
    public static string $primaryKey = 'AlbumId';

    /**
     * @var Dbms $dbms
     */
    protected static Dbms $dbms = Dbms::Sqlite;

    /**
     * @var array<string> $columns
     */
    protected static array $columns = [
        'AlbumId',
        'Title',
        'ArtistId',
    ];

    /**
     * @var int $AlbumId
     */
    public int $AlbumId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $Title
     */
    public string $Title;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $ArtistId
     */
    public int $ArtistId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
