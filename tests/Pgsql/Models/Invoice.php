<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;

/**
 * @method static bool|Invoice first(array<string, string> $columnValues)
 * @method bool|array<Invoice> get()
 * @method bool|Invoice getOne()
 */
class Invoice extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'invoice';

    /**
     * @var array<string> $columns
     */
    protected static array $columns = [
        'InvoiceId',
        'CustomerId',
        'InvoiceDate',
        'Total',
        'BillingCity',
        'BillingState',
        'BillingCountry',
        'BillingPostalCode',
        'BillingAddress',
    ];

    /**
     * @var int $InvoiceId
     */
    public int $InvoiceId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $CustomerId
     */
    public int $CustomerId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $InvoiceDate
     */
    public string $InvoiceDate;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var float $Total
     */
    public float $Total;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $BillingCity
     */
    public string $BillingCity;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $BillingState
     */
    public string $BillingState;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $BillingCountry
     */
    public string $BillingCountry;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $BillingPostalCode
     */
    public string $BillingPostalCode;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $BillingAddress
     */
    public string $BillingAddress;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
