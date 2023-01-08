<?php
declare(strict_types=1);

namespace SqlModels\Tests\Pgsql\Models;


class SqlGenerator
{


    /**
     * @param string $tableName
     * @param array<string, string> $params
     * @param array<string> $selectedColumns
     * @param null|array<int, array<string, string>> $joins
     * @param null|array<int, array<string, string>> $where
     * @param null|string $groupBy
     * @param null|array<int, array<string, string>> $having
     * @param null|array<int, array<string, string>> $orderBy
     * @param int $limit
     * @param int $offset
     * @return string
     */
    public static function generateSelect(
        string $tableName,
        array &$params,
        array $selectedColumns,
        null|array $joins=null,
        null|array $where=null,
        null|string $groupBy=null,
        null|array $having=null,
        null|array $orderBy=null,
        int $limit=1000,
        int $offset=0
    ): string {
        $c = self::generateColumns($selectedColumns);

        $j = self::generateJoins($joins);

        $w = self::generateConditions('WHERE', $where, $params);

        $g = self::generateGroupBy($groupBy);

        $h = self::generateConditions('HAVING', $having, $params);

        $o = self::generateOrderBy($orderBy);

        $l = self::generateLimit($limit);

        $of = self::generateOffsset($offset);

        return "SELECT $c FROM $tableName $j$w$g$h$o$l$of";

    }//end generateSelect()


    /**
     * @param string $tableName
     * @param array<string, string> $params
     * @return string
     */
    public static function generateInsert(string $tableName, array $params): string
    {
        $cols    = array_keys($params);
        $columns = self::generateColumns($cols);
        $values  = self::generateValues($cols);

        return "INSERT INTO $tableName ($columns) VALUES (:$values);";

    }//end generateInsert()


    /**
     * @param string $tableName
     * @param array<string, string> $params
     * @param array<int, array<string, string>> $where
     * @param null|array<int, array<string, string>> $orderBy
     * @param int $limit
     * @return string
     */
    public static function generateUpdate(string $tableName, array &$params, array $where=[], null|array $orderBy=null, int $limit=0)
    {
        $s = self::generateSet(array_keys($params));

        $w = self::generateConditions('WHERE', $where, $params);

        $o = self::generateOrderBy($orderBy);

        $l = ($limit > 0) ? self::generateLimit($limit): '';

        return "UPDATE $tableName SET $s$w$o$l";

    }//end generateUpdate()


    /**
     * @param string $tableName
     * @param array<string, string> $params
     * @param array<int, array<string, string>> $where
     * @param null|array<int, array<string, string>> $orderBy
     * @param int $limit
     * @return string
     */
    public static function generateDelete(string $tableName, array &$params, array $where=[], null|array $orderBy=null, int $limit=0): string
    {
        $w = self::generateConditions('WHERE', $where, $params);

        $o = self::generateOrderBy($orderBy);

        $l = ($limit > 0) ? self::generateLimit($limit): '';

        return "DELETE FROM $tableName $w";

    }//end generateDelete()


    /**
     * @param array<int, string> $cols
     * @return string
     */
    protected static function generateColumns(array $cols): string
    {
        return implode(', ', $cols);

    }//end generateColumns()


    /**
     * @param null|array<int, array<string, string>> $joins
     * @return string
     */
    protected static function generateJoins(null|array $joins): string
    {
        if (empty($joins)) {
            return '';
        }

        $sql = '';

        foreach ($joins as $j) {
            $sql .= sprintf(' %s JOIN %s ON %s = "%s"', $j['type'], $j['tableName'], $j['onCol1'], $j['onCol2']);
        }

        return $sql;

    }//end generateJoins()


    /**
     * @param string $type
     * @param array<int, array<string, string>> $conditions
     * @param array<string, string> $params
     * @return string
     */
    protected static function generateConditions(string $type, array $conditions, array &$params): string
    {
        if (empty($conditions) ) {
            return '';
        }

        $sql = " $type 1";

        foreach ($conditions as $i => $c) {
            $operator = $c['operator'];
            $col      = $c['column'];

            if ($operator != 'IN') {
                $sql .= sprintf(' %s %s %s :%s%s', $c['condition_operator'], $col, $c['operator'], $col, $i);
                $params["$col$i"] = $c['value'];
            } else {
                $sql .= sprintf(' %s %s IN %s', $c['condition_operator'], $col, $c['value']);
            }
        }

        return $sql;

    }//end generateConditions()


    /**
     * @param null|string $groupBy
     * @return string
     */
    protected static function generateGroupBy(null|string $groupBy): string
    {
        if (empty($groupBy)) {
            return '';
        }

        return " GROUP BY $groupBy";

    }//end generateGroupBy()


    /**
     * @param int $limit
     * @return string
     */
    protected static function generateLimit(int $limit=100): string
    {
        return " LIMIT $limit";

    }//end generateLimit()


    /**
     * @param int $offset
     * @return string
     */
    protected static function generateOffsset(int $offset=0): string
    {
        return " OFFSET $offset";

    }//end generateOffsset()


    /**
     * @param null|array<array<string, string>> $orderBy
     * @return string
     */
    protected static function generateOrderBy(null|array $orderBy): string
    {
        if (empty($orderBy)) {
            return '';
        }

        $sql = ' ORDER BY ';

        foreach ($orderBy as $order) {
            $sql .= implode(' ', $order);
        }

        return $sql;

    }//end generateOrderBy()


    /**
     * @param array<int, string> $cols
     * @return string
     */
    protected static function generateValues(array $cols): string
    {
        return implode(', :', $cols);

    }//end generateValues()


    /**
     * @param array<int, string> $cols
     * @return string
     */
    protected static function generateSet(array $cols): string
    {
        $sql = '';
        
        foreach ($cols as $c) {
            $sql .= " $c = :$c,";
        }

        return substr($sql, 0, -1);

    }//end generateValues()


}//end class
