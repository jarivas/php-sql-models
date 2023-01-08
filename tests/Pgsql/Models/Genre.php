<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;

/**
 * @method static bool|Genre first(array<string, string> $columnValues)
 * @method bool|array<Genre> get()
 * @method bool|Genre getOne()
 */
class Genre extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'genre';

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
     * @var string $Name
     */
    public string $Name;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
