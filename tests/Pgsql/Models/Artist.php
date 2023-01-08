<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;

/**
 * @method static bool|Artist first(array<string, string> $columnValues)
 * @method bool|array<Artist> get()
 * @method bool|Artist getOne()
 */
class Artist extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'artist';

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
     * @var string $Name
     */
    public string $Name;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
