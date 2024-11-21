<?php
declare(strict_types=1);

namespace SqlModels\Tests\Mssql\Models;

use Exception;
use JsonSerializable;

class Model implements JsonSerializable
{

    /**
     * @var string $tableName
     */
    protected static string $tableName = '';

    /**
     * @var string $primaryKey
     */
    protected static string $primaryKey = 'id';

    /**
     * @var Dbms $dbms
     */
    protected static Dbms $dbms;

    /**
     * @var array<string> $columns
     */
    protected static array $columns = [];

    /**
     * @var array<string> $selectedColumns
     */
    protected array $selectedColumns = [];

    /**
     * @var array<int, array<string, string>> $joins
     */
    protected array $joins = [];

    /**
     * @var array<int, array<string, string>>  $where
     */
    protected array $where = [];

    /**
     * @var null|string $groupBy
     */
    protected null|string $groupBy = null;

    /**
     * @var array<int, array<string, string>>  $having
     */
    protected $having = [];

    /**
     * @var array<int, array<string, string>>  $order
     */
    protected array $order = [];

    /**
     * @var int $limit
     */
    protected int $limit = 1;

    /**
     * @var int $limit
     */
    protected int $offset = 0;

    public static function getColumns(): array
    {
        return static::$columns;
    }

    /**
     * @param array<string, mixed> $columnValues
     * @return bool|static
     */
    public static function first(array $columnValues=[]): bool|static
    {
        $rows = self::get($columnValues, 0, 1);

        return $rows ? $rows[0] : false;

    }//end first()


    public static function last(array $columnValues=[]): bool|static
    {
        $rows = self::get(
            $columnValues,
            0,
            1,
            [],
            [static::$primaryKey => 'DESC'],
        );

        return $rows ? $rows[0] : false;

    }//end last()


    /**
     * @param array<string, mixed> $columnValues
     * @param int $offset
     * @param int $limit
     * @param array<string> $columns
     * @param array<string> $orderBy
     * @return bool|array<static>
     */
    public static function get(array $columnValues=[], int $offset=0, int $limit=100, array $columns=[], array $orderBy=[]): bool|array
    {
        // @phpstan-ignore-next-line
        $new = new static();

        $new->select($columns);

        $new->setValues($columnValues, true);

        $new->offset($offset);
        $new->limit($limit);
        $new->order($orderBy);

        $rows = $new->query(false);

        if (! is_array($rows) || empty($rows)) {
            return false;
        }

        return $rows;

    }//end get()


    /**
     * @param null|array<string, mixed> $columnValues
     */
    public function __construct(null|array $columnValues=null)
    {
        if (!empty($columnValues)) {
            $this->setValues($columnValues);
        }

    }//end __construct()


    /**
     * @param array<string, mixed> $columnValues
     * @param bool $where
     */
    public function setValues(array $columnValues, bool $where=false): void
    {
        foreach ($columnValues as $column => $value) {
            if (in_array($column, static::$columns)) {
                $this->$column = $value;

                if ($where) {
                    $this->where[] = [
                        'condition_operator' => 'AND',
                        'column'             => $column,
                        'operator'           => '=',
                    ];
                }
            }
        }

    }//end setValues()


    /**
     * @param array<string> $columns
     */
    public function select(array $columns): void
    {
        $this->selectedColumns = [];

        if (empty($columns)) {
            return;
        }

        foreach ($columns as $column) {
            if (in_array($column, static::$columns)) {
                $this->selectedColumns[] = $column;
            }
        }

    }//end select()


    /**
     * @param Join $joinType
     * @param string $tableName
     * @param string $onCol1
     * @param string $onCol2
     */
    public function join(Join $joinType, string $tableName, string $onCol1, string $onCol2): void
    {
        $this->joins[] = [
            'type'      => $joinType->value,
            'tableName' => $tableName,
            'onCol1'    => $onCol1,
            'onCol2'    => $onCol2,
        ];

    }//end join()


    /**
     * @param string $column
     * @param string $operator
     * @param string $operator
     */
    public function where(string $column, string $operator, mixed $value=null): void
    {
        $this->where[] = $this->whereHelper('AND', $column, $operator, $value);

    }//end where()


    /**
     * @param string $column
     * @param string $operator
     */
    public function whereOr(string $column, string $operator, mixed $value=null): void
    {
        $this->where[] = $this->whereHelper('OR', $column, $operator, $value);

    }//end whereOr()


    /**
     * @param string $column
     * @param string $operator
     */
    public function having(string $column, string $operator, mixed $value=null): void
    {
        $this->having[] = $this->whereHelper('AND', $column, $operator, $value);

    }//end having()


    /**
     * @param string $column
     * @param string $operator
     * @param mixed $value
     */
    public function havingOr(string $column, string $operator, mixed $value=null): void
    {
        $this->having[] = $this->whereHelper('OR', $column, $operator, $value);

    }//end havingOr()


    /**
     * @param string  $column
     */
    public function groupBy(string $column): void
    {
        $this->groupBy = $column;

    }//end groupBy()


    /**
     * @param array<string> $order
     */
    public function order(array $order): void
    {
        if (!empty($order)) {
            foreach ($order as $col => $direction) {
                $this->order[$col] = $direction;
            }
        }

    }//end order()


    /**
     * @param int $limit
     */
    public function limit(int $limit): void
    {
        $this->limit = $limit;

    }//end limit()


    /**
     * @param int $offset
     */
    public function offset(int $offset): void
    {
        $this->offset = $offset;

    }//end offset()


    /**
     * @param string|null $pk on tables with primary no autoincrement, please use null
     *
     * @return bool
     */
    public function delete(): bool
    {
        $pk = static::$primaryKey;

        if (empty($this->$pk)) {
            return false;
        }

        $params = [];

        $this->where($pk, '=');

        $sql = SqlGenerator::generateDelete(static::$dbms, static::$tableName, $params, $this->criteriaHelper($this->where));

        unset($this->$pk);

        $this->resetFilters();

        Connection::getInstance()->executeSql($sql, $params);

        return true;

    }//end delete()


    /**
     * @param bool $resetFilter
     * @param bool $debug
     *
     * @return bool|array<static> array of current model
     */
    public function query(bool $resetFilter=true, bool $debug=false): mixed
    {
        $cols   = static::$columns;
        $params = [];

        if (! empty($this->selectedColumns)) {
            $cols = $this->selectedColumns;

            $this->selectedColumns = [];
        }

        $sql = SqlGenerator::generateSelect(
            static::$dbms,
            static::$tableName,
            $params,
            $cols,
            $this->joins,
            $this->criteriaHelper($this->where),
            $this->groupBy,
            $this->criteriaHelper($this->having),
            $this->orderHelper(),
            $this->limit,
            $this->offset
        );

        if ($resetFilter) {
            $this->resetFilters();
        }

        if ($debug) {
            error_log($sql);
            error_log(print_r($params, true));
        }

        return Connection::getInstance()->get($sql, $params, static::class);

    }//end query()


    public function save(): void
    {
        $pk = static::$primaryKey;

        isset($this->$pk) ? $this->update() : $this->insert();

    }//end save()


    public function hydrate(): void
    {
        $pk = static::$primaryKey;

        if (empty($this->$pk)) {
            return;
        }

        $this->limit  = 1;
        $this->offset = 0;
        $this->where($pk,'=');

        $rows = $this->query(false);

        if (! is_bool($rows)) {
            $this->setValues($rows[0]->toArray());
        }

    }//end hydrate()


    public function __toString(): string
    {
        $data = $this->columnsToParams();

        return strval(json_encode($data, JSON_PRETTY_PRINT));

    }//end __toString()


    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): mixed
    {
        return $this->columnsToParams();

    }//end jsonSerialize()


    /**
     * @return array<string, mixed>
     */
    public function toArray(): mixed
    {
        return $this->columnsToParams();

    }//end toArray()


    /**
     * @param string $conditionOperator
     * @param string $column
     * @param string $operator
     * @param mixed $value
     */
    protected function whereHelper(string $conditionOperator, string $column, string $operator, mixed $value): array
    {
        return [
            'condition_operator' => $conditionOperator,
            'column'             => $column,
            'operator'           => $operator,
            'value'              => $value,
        ];

    }//end whereHelper()


    protected function columnsToParams(): array
    {
        $params = [];

        foreach (static::$columns as $column) {
            if (isset($this->$column)) {
                $params[$column] = $this->$column;
            }
        }

        return $params;

    }//end columnsToParams()


    public function insert(): void
    {
        $params     = $this->columnsToParams();
        $connection = Connection::getInstance();

        $sql = SqlGenerator::generateInsert(static::$dbms, static::$tableName, $params);

        $connection->executeSql($sql, $params);

        $this->setLastInsertId($connection);

    }//end insert()


    public function update(): void
    {
        $pk     = static::$primaryKey;
        $params = $this->columnsToParams();

        $this->where($pk, '=');

        $sql = SqlGenerator::generateUpdate(
            static::$dbms,
            static::$tableName,
            $params,
            $this->criteriaHelper($this->where)
        );

        $this->resetFilters();

        Connection::getInstance()->executeSql($sql, $params);

    }//end update()


    /**
     * @param array $criteria array<string, mixed>
     * @return array
     */
    protected function criteriaHelper(array $criteria): array
    {
        $result = [];

        if (empty($criteria)) {
            return $result;
        }

        foreach ($criteria as $c) {
            $field = $c['column'];

            if (empty($c['value'])) {
                $c['value'] = $this->$field;
            }

            $result[] = $c;
        }

        return $result;

    }//end criteriaHelper()


    protected function orderHelper(): array
    {
        $order = [];

        if (empty($this->order)) {
            $this->order[static::$primaryKey] = 'ASC';
        }

        return $this->order;

    }//end orderHelper()


    protected function setLastInsertId(Connection $connection): void
    {
        $pk = static::$primaryKey;

        if (!empty($this->$pk)) {
            return;
        }

        $id = null;

        try {
            $id = $connection->lastInsertId();

            if (!$id) {
                throw new Exception('Last id not working');
            }
        } catch (Exception $exeption) {
            $id = $this->getLastInsertIdHelper();
        }

        $this->$pk = intval($id);

    }//end setLastInsertId()


    protected function getLastInsertIdHelper(): mixed
    {
        $pk    = static::$primaryKey;
        $model = static::last([$pk]);

        return $model->$pk;

    }//end getLastInsertIdHelper()


    protected function resetFilters(): void
    {
        $this->joins   = [];
        $this->where   = [];
        $this->groupBy = '';
        $this->having  = [];
        $this->order   = [];
        $this->limit   = 10;
        $this->offset  = 0;

    }//end resetFilters()


}//end class
