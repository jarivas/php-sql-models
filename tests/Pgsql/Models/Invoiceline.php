<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;

/**
 * @method static bool|Invoiceline first(array<string, string> $columnValues)
 * @method bool|array<Invoiceline> get()
 * @method bool|Invoiceline getOne()
 */
class Invoiceline extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'invoiceline';

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
