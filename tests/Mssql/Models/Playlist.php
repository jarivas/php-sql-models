<?php
declare(strict_types=1);

namespace SqlModels\Tests\Mssql\Models;

/**
 * @method static bool|Playlist first(array<string, mixed> $columnValues)
 * @method static bool|Playlist[] get(array<string, mixed> $columnValues = [], int $offset = 0, int $limit = 100, array $columns = []): bool|array
 */
class Playlist extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'Playlist';

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
        'Name',
    ];

    /**
     * @var int $PlaylistId
     */
    public int $PlaylistId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $Name
     */
    public ?string $Name;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
