<?php
declare(strict_types=1);

namespace SqlModels\Tests\Mssql\Models;

/**
 * @method static bool|MediaType first(array<string, mixed> $columnValues)
 * @method static bool|MediaType[] get(array<string, mixed> $columnValues = [], int $offset = 0, int $limit = 100, array $columns = []): bool|array
 */
class MediaType extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'MediaType';

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
