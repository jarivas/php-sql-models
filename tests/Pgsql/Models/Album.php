<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;

/**
 * @method static bool|Album first(array<string, mixed> $columnValues)
 * @method static bool|Album[] get(array<string, mixed> $columnValues = [], int $offset = 0, int $limit = 100, array $columns = []): bool|array
 */
class Album extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'Album';

    /**
     * @var string $primaryKey
     */
    protected static string $primaryKey = 'AlbumId';

    /**
     * @var Dbms $dbms
     */
    protected static Dbms $dbms = Dbms::Pgsql;

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
