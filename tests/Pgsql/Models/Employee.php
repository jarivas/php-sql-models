<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;

/**
 * @method static bool|Employee first(array<string, string> $columnValues)
 * @method bool|array<Employee> get()
 * @method bool|Employee getOne()
 */
class Employee extends Model
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = 'employee';

    /**
     * @var array<string> $columns
     */
    protected static array $columns = [
        'BirthDate',
        'HireDate',
        'ReportsTo',
        'EmployeeId',
        'Address',
        'City',
        'State',
        'Country',
        'PostalCode',
        'Phone',
        'Fax',
        'Email',
        'LastName',
        'FirstName',
        'Title',
    ];

    /**
     * @var string $BirthDate
     */
    public string $BirthDate;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $HireDate
     */
    public string $HireDate;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $ReportsTo
     */
    public int $ReportsTo;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var int $EmployeeId
     */
    public int $EmployeeId;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $Address
     */
    public string $Address;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $City
     */
    public string $City;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $State
     */
    public string $State;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $Country
     */
    public string $Country;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $PostalCode
     */
    public string $PostalCode;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $Phone
     */
    public string $Phone;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $Fax
     */
    public string $Fax;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $Email
     */
    public string $Email;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $LastName
     */
    public string $LastName;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $FirstName
     */
    public string $FirstName;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

    /**
     * @var string $Title
     */
    public string $Title;// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps


}//end class
