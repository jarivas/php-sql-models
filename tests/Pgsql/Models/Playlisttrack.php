<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;

/**
 * @method static bool|Playlisttrack first(array<string, string> $columnValues)
 * @method bool|array<Playlisttrack> get()
 * @method bool|Playlisttrack getOne()
 */
class Playlisttrack extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'playlisttrack';

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
