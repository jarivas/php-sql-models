<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;

/**
 * @method static bool|Genre first(array<string, mixed> $columnValues)
 * @method static bool|Genre[] get(array<string, mixed> $columnValues = [], int $offset = 0, int $limit = 100, array $columns = []): bool|array
 */
class Genre extends Model
{

    /**
     * @var string $tableName
     */
    public static string $tableName = 'Genre';

    /**
     * @var string $primaryKey
     */
    public static string $primaryKey = 'id';

    /**
     * @var Dbms $dbms
     */
    protected static Dbms $dbms = Dbms::Pgsql;

    /**
     * @var array<string> $columns
     */
    protected static array $columns = [
        'GenreId',
        'Name',
    ];

    /**
     * @var int $GenreId
     */
    public int $GenreId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $Name
     */
    public ?string $Name;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
