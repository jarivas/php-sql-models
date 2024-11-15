<?php
declare(strict_types=1);

namespace SqlModels\Tests\Mysql\Models;

/**
 * @method static bool|MediaType first(array<string, mixed> $columnValues)
 * @method static bool|MediaType[] get(array<string, mixed> $columnValues = [], int $offset = 0, int $limit = 100, array $columns = []): bool|array
 */
class MediaType extends Model
{

    /**
     * @var string $tableName
     */
    public static string $tableName = 'MediaType';

    /**
     * @var string $primaryKey
     */
    public static string $primaryKey = 'id';

    /**
     * @var Dbms $dbms
     */
    protected static Dbms $dbms = Dbms::Mysql;

    /**
     * @var array<string> $columns
     */
    protected static array $columns = [
        'MediaTypeId',
        'Name',
    ];

    /**
     * @var int $MediaTypeId
     */
    public int $MediaTypeId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $Name
     */
    public ?string $Name;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
