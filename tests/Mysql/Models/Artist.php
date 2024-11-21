<?php
declare(strict_types=1);

namespace SqlModels\Tests\Mysql\Models;

/**
 * @method static bool|Artist first(array<string, mixed> $columnValues)
 * @method static bool|Artist[] get(array<string, mixed> $columnValues = [], int $offset = 0, int $limit = 100, array $columns = []): bool|array
 */
class Artist extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'Artist';

    /**
     * @var string $primaryKey
     */
    protected static string $primaryKey = 'id';

    /**
     * @var Dbms $dbms
     */
    protected static Dbms $dbms = Dbms::Mysql;

    /**
     * @var array<string> $columns
     */
    protected static array $columns = [
        'ArtistId',
        'Name',
    ];

    /**
     * @var int $ArtistId
     */
    public int $ArtistId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $Name
     */
    public ?string $Name;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
