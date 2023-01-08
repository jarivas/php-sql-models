<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;

/**
 * @method static bool|Mediatype first(array<string, string> $columnValues)
 * @method bool|array<Mediatype> get()
 * @method bool|Mediatype getOne()
 */
class Mediatype extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'mediatype';

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
     * @var string $Name
     */
    public string $Name;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
