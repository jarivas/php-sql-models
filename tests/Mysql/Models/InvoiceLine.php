<?php
declare(strict_types=1);

namespace SqlModels\Tests\Mysql\Models;

/**
 * @method static bool|InvoiceLine first(array<string, mixed> $columnValues)
 * @method static bool|InvoiceLine[] get(array<string, mixed> $columnValues = [], int $offset = 0, int $limit = 100, array $columns = []): bool|array
 */
class InvoiceLine extends Model
{

    /**
     * @var string $tableName
     */
    public static string $tableName = 'InvoiceLine';

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
        'InvoiceLineId',
        'InvoiceId',
        'TrackId',
        'UnitPrice',
        'Quantity',
    ];

    /**
     * @var int $InvoiceLineId
     */
    public int $InvoiceLineId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $InvoiceId
     */
    public int $InvoiceId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $TrackId
     */
    public int $TrackId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var float $UnitPrice
     */
    public float $UnitPrice;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $Quantity
     */
    public int $Quantity;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
