<?php
declare(strict_types=1);

namespace SqlModels\Tests\Mssql\Models;

/**
 * @method static bool|PlaylistTrack first(array<string, mixed> $columnValues)
 * @method static bool|PlaylistTrack[] get(array<string, mixed> $columnValues = [], int $offset = 0, int $limit = 100, array $columns = []): bool|array
 */
class PlaylistTrack extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'PlaylistTrack';

    /**
     * @var string $primaryKey
     */
    protected static string $primaryKey = 'id';

    /**
     * @var Dbms $dbms
     */
    protected static Dbms $dbms = Dbms::Mssql;

    /**
     * @var array<string> $columns
     */
    protected static array $columns = [
        'PlaylistId',
        'TrackId',
    ];

    /**
     * @var int $PlaylistId
     */
    public int $PlaylistId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $TrackId
     */
    public int $TrackId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
