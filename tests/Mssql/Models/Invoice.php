<?php
declare(strict_types=1);

namespace SqlModels\Tests\Mssql\Models;

/**
 * @method static bool|Invoice first(array<string, mixed> $columnValues)
 * @method static bool|Invoice[] get(array<string, mixed> $columnValues = [], int $offset = 0, int $limit = 100, array $columns = []): bool|array
 */
class Invoice extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'Invoice';

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
        'InvoiceId',
        'CustomerId',
        'InvoiceDate',
        'BillingAddress',
        'BillingCity',
        'BillingState',
        'BillingCountry',
        'BillingPostalCode',
        'Total',
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
     * @var ?string $BillingAddress
     */
    public ?string $BillingAddress;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $BillingCity
     */
    public ?string $BillingCity;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $BillingState
     */
    public ?string $BillingState;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $BillingCountry
     */
    public ?string $BillingCountry;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $BillingPostalCode
     */
    public ?string $BillingPostalCode;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var float $Total
     */
    public float $Total;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
