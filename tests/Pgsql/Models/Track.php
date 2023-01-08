<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;

/**
 * @method static bool|Track first(array<string, string> $columnValues)
 * @method bool|array<Track> get()
 * @method bool|Track getOne()
 */
class Track extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'track';

    /**
     * @var array<string> $columns
     */
    protected static array $columns = [
        'UnitPrice',
        'Bytes',
        'TrackId',
        'AlbumId',
        'MediaTypeId',
        'GenreId',
        'Milliseconds',
        'Name',
        'Composer',
    ];

    /**
     * @var float $UnitPrice
     */
    public float $UnitPrice;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $Bytes
     */
    public int $Bytes;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $TrackId
     */
    public int $TrackId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $AlbumId
     */
    public int $AlbumId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $MediaTypeId
     */
    public int $MediaTypeId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $GenreId
     */
    public int $GenreId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $Milliseconds
     */
    public int $Milliseconds;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $Name
     */
    public string $Name;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $Composer
     */
    public string $Composer;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
