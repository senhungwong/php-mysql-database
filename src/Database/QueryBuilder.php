<?php

namespace Senhung\DB\Database;

class QueryBuilder
{
    /* Reference Options */
    public const RESTRICT = 'RESTRICT';
    public const CASCADE = 'CASCADE';
    public const SET_NULL = 'SET NULL';
    public const NO_ACTION = 'NO ACTION';
    public const SET_DEFAULT = 'SET DEFAULT';

    /** @var string $query */
    private $query = '';

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->query;
    }

    /**
     * Clear current query
     */
    public function clear(): void
    {
        $this->query = '';
    }

    /**
     * SELECT ...
     *
     * @param array|string $columns
     * @return QueryBuilder
     */
    public function select($columns): QueryBuilder
    {
        $this->query .= 'SELECT ';

        if (is_array($columns)) {
            $this->query .= "'" . implode("', '", $columns) . "' ";
        } else {
            /* Check select all */
            if ($columns != '*') {
                $columns = "'" . $columns . "'";
            }

            $this->query .= $columns . ' ';
        }

        return $this;
    }

    /**
     * FROM ...
     *
     * @param string $table
     * @return QueryBuilder
     */
    public function from(string $table): QueryBuilder
    {
        $this->query .= 'FROM `' . $table . '` ';

        return $this;
    }

    /**
     * WHERE ...
     *
     * @param array $conditions
     * @return QueryBuilder
     */
    public function where(array $conditions): QueryBuilder
    {
        $this->query .= 'WHERE ';

        /* One Condition: ['name', '=', 'alex'] */
        if (gettype($conditions[0]) != 'array') {
            $this->query .= "`" . $conditions[0] . "`" . $conditions[1] . "'" . $conditions[2] . "' ";
        }

        /* Multiple Conditions: [
            ['name', '=', 'alex'],
            ['age', '>', '20']
        ] */
        else {
            $cons = [];
            foreach ($conditions as $condition) {
                $cons[] = implode(" ", $condition);
            }
            $this->query .= implode(" AND ", $cons) . ' ';
        }

        return $this;
    }

    /**
     * INSERT INTO ...
     *
     * @param string $table
     * @param array $fields
     * @return QueryBuilder
     */
    public function insertInto(string $table, array $fields): QueryBuilder
    {
        $this->query .= 'INSERT INTO `' . $table . '` (`' . implode('`, `', $fields) . '`) ';

        return $this;
    }

    /**
     * VALUES ...
     *
     * @param array $values
     * @return QueryBuilder
     */
    public function values(array $values): QueryBuilder
    {
        $this->query .= "VALUES ('" . implode("', '", $values) . "') ";

        return $this;
    }

    /**
     * UPDATE ...
     *
     * @param string $table
     * @return QueryBuilder
     */
    public function update(string $table): QueryBuilder
    {
        $this->query .= "UPDATE `" . $table . "` ";

        return $this;
    }

    /**
     * SET ...
     *
     * @param array $fields
     * @return QueryBuilder
     */
    public function set(array $fields): QueryBuilder
    {
        $this->query .= "SET ";

        $attributes = [];

        foreach ($fields as $field => $value) {
            $attributes[] = "`" . $field . "`='" . $value . "' ";
        }

        $this->query .= implode(', ', $attributes);

        return $this;
    }
}
