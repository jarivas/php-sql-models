<?php
declare(strict_types=1);

namespace SqlModels\Tests\Mysql\Models;

/**
 * @method static bool|Customer first(array<string, mixed> $columnValues)
 * @method static bool|Customer[] get(array<string, mixed> $columnValues = [], int $offset = 0, int $limit = 100, array $columns = []): bool|array
 */
class Customer extends Model
{

    /**
     * @var string $tableName
     */
    public static string $tableName = 'Customer';

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
        'CustomerId',
        'FirstName',
        'LastName',
        'Company',
        'Address',
        'City',
        'State',
        'Country',
        'PostalCode',
        'Phone',
        'Fax',
        'Email',
        'SupportRepId',
    ];

    /**
     * @var int $CustomerId
     */
    public int $CustomerId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $FirstName
     */
    public string $FirstName;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $LastName
     */
    public string $LastName;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $Company
     */
    public ?string $Company;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $Address
     */
    public ?string $Address;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $City
     */
    public ?string $City;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $State
     */
    public ?string $State;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $Country
     */
    public ?string $Country;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $PostalCode
     */
    public ?string $PostalCode;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $Phone
     */
    public ?string $Phone;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?string $Fax
     */
    public ?string $Fax;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $Email
     */
    public string $Email;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var ?int $SupportRepId
     */
    public ?int $SupportRepId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
