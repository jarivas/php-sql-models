<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;

/**
 * @method static bool|Playlist first(array<string, string> $columnValues)
 * @method bool|array<Playlist> get()
 * @method bool|Playlist getOne()
 */
class Playlist extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'playlist';

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
     * @var string $Name
     */
    public string $Name;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
