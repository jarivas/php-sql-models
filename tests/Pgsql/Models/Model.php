<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;

use Exception;
use JsonSerializable;

class Model implements JsonSerializable
{

    /**
     * @var array<string> $columns
     */
    protected static array $columns = [];

    /**
     * @var string $tableName
     */
    protected static string $tableName = '';

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
    protected int $limit = 100;

    /**
     * @var int $limit
     */
    protected int $offset = 0;


    /**
     * @param array<string, string> $columnValues
     * @return bool|static
     */
    public static function first(array $columnValues): mixed
    {
        // @phpstan-ignore-next-line
        $new = new static();

        foreach ($columnValues as $column => $value) {
            $new->where($column, '=', $value);
        }

        return $new->getOne();

    }//end first()


    /**
     * @param array<string> $columns
     */
    public function select(array $columns): void
    {
        if (empty($columns)) {
            return;
        }

        $this->selectedColumns = $columns;

    }//end select()


    /**
     * @param string $type
     * @param string $tableName
     * @param string $onCol1
     * @param string $onCol2
     */
    public function join(string $type, string $tableName, string $onCol1, string $onCol2): void
    {
        $this->joins[] = [
            'type'      => $type,
            'tableName' => $tableName,
            'onCol1'    => $onCol1,
            'onCol2'    => $onCol2,
        ];

    }//end join()


    /**
     * @param string $column
     * @param string $operator
     * @param mixed $value
     */
    public function where(string $column, string $operator, mixed $value): void
    {
        $this->where[] = [
            'condition_operator' => 'AND',
            'column'             => $column,
            'operator'           => $operator,
            'value'              => $value,
        ];

    }//end where()


    /**
     * @param string $column
     * @param string $operator
     * @param mixed $value
     */
    public function whereOr(string $column, string $operator, mixed $value): void
    {
        $this->where[] = [
            'condition_operator' => 'OR',
            'column'             => $column,
            'operator'           => $operator,
            'value'              => $value,
        ];

    }//end whereOr()


    /**
     * @param string $column
     * @param string $operator
     * @param mixed $value
     */
    public function having(string $column, string $operator, mixed $value): void
    {
        $this->having[] = [
            'condition_operator' => 'AND',
            'column'             => $column,
            'operator'           => $operator,
            'value'              => $value,
        ];

    }//end having()


    /**
     * @param string  $column
     */
    public function groupBy(string $column): void
    {
        $this->groupBy = $column;

    }//end groupBy()


    /**
     * @param string $column
     * @param string $operator
     * @param mixed $value
     */
    public function havingOr(string $column, string $operator, mixed $value): void
    {
        $this->having[] = [
            'condition_operator' => 'OR',
            'column'             => $column,
            'operator'           => $operator,
            'value'              => $value,
        ];

    }//end havingOr()


    /**
     * @param array<string, string>  $order
     */
    public function order(array $order): void
    {
        $this->order[] = $order;

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
     * @return bool|array<static> array of current model
     */
    public function get(): mixed
    {
        $cols   = static::$columns;
        $params = [];

        if (! empty($this->selectedColumns)) {
            $cols = $this->selectedColumns;

            $this->selectedColumns = [];
        }

        $sql = SqlGenerator::generateSelect(
            static::$tableName,
            $params,
            $cols,
            $this->joins,
            $this->where,
            $this->groupBy,
            $this->having,
            $this->order,
            $this->limit,
            $this->offset
        );

        $this->resetFilters();

        return Connection::getInstance()->get($sql, $params, static::class);

    }//end get()


    /**
     * @param array<string, string> $columnValues
     * @return bool|static
     */
    public function getOne(): mixed
    {
        $this->limit(1);

        $rows = $this->get();

        if (! is_array($rows) || empty($rows)) {
            return false;
        }

        return $rows[0];

    }//end getOne()


    public function delete(string $pk='id'): bool
    {
        if (empty($this->$pk)) {
            return false;
        }

        $params = [];

        $this->where($pk, '=', $this->$pk);

        $sql = SqlGenerator::generateDelete(static::$tableName, $params, $this->where);

        unset($this->$pk);

        $this->resetFilters();

        Connection::getInstance()->executeSql($sql, $params);

        return true;

    }//end delete()

    /**
     * on tables with primary no autoincrement, please use null
     */
    public function save(string|null $pk='id'): void
    {
        isset($this->$pk) ? $this->update($pk) : $this->insert($pk);

    }//end save()


    public function __toString(): string
    {
        $data = $this->columnsToParams();

        return strval(json_encode($data, JSON_PRETTY_PRINT));

    }//end __toString()


    public function jsonSerialize(): mixed
    {
        return $this->columnsToParams();

    }//end jsonSerialize()


    /**
     * @return array<string, string>
     */
    protected function columnsToParams(bool|string $pk=null): array
    {
        $params = [];

        foreach (static::$columns as $column) {
            if (isset($this->$column)) {
                $params[$column] = $this->$column;
            }
        }

        if ($pk && isset($this->$pk)) {
            unset($params[$pk]);
        }

        return $params;

    }//end columnsToParams()


    protected function insert(string|null $pk='id'): void
    {
        $params     = $this->columnsToParams($pk);
        $connection = Connection::getInstance();

        $sql = SqlGenerator::generateInsert(static::$tableName, $params);

        $connection->executeSql($sql, $params);

        if (is_null($pk)) {
            return;
        }

        $id = $connection->lastInsertId();

        if (!$id) {
            throw new Exception('empty lastInsertId');
        }

        $this->$pk = intval($id);

    }//end insert()


    protected function update(string $pk='id'): void
    {
        $params = $this->columnsToParams($pk);

        $this->where($pk, '=', $this->$pk);

        $sql = SqlGenerator::generateUpdate(static::$tableName, $params, $this->where);

        $this->resetFilters();

        Connection::getInstance()->executeSql($sql, $params);

    }//end update()


    protected function resetFilters(): void
    {
        $this->joins  = [];
        $this->where  = [];
        $this->groupBy  = '';
        $this->having = [];
        $this->order  = [];
        $this->limit  = 10;
        $this->offset = 0;

    }//end resetFilters()


}//end class
