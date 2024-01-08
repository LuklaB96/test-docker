<?php
namespace App\Lib\Database\Helpers;

use App\Lib\Database\Interface\QueryBuilderInterface;
use App\Lib\Database\Mapping\Attributes\Column;

/**
 * This class helps creating queries like INSERT|UPDATE|SELECT|DELETE|CREATE etc. from variable attributes and class properties
 */
class QueryBuilder implements QueryBuilderInterface
{
    public static function createTable(array $columns, string $tableName, #[\SensitiveParameter] string $dbname, bool $checkTableExists = false): string
    {

        $query = "CREATE TABLE `$dbname`.`$tableName` (";
        if ($checkTableExists) {
            $query = "CREATE TABLE IF NOT EXISTS `$dbname`.`$tableName` (";
        }

        $columnDefinitions = [];
        foreach ($columns as $column) {

            $columnDefinitions[] = "`$column->name`" . ' ' . self::createColumnDefinition($column);
        }

        $query .= implode(', ', $columnDefinitions);
        $query .= ");";
        return $query;
    }
    public static function createColumnDefinition(Column $column): string
    {
        $definition = strtoupper($column->type->value);
        if ($column->length > 0) {
            $definition .= "($column->length)";
        }
        if ($column->autoIncrement) {
            $definition .= ' AUTO_INCREMENT';
        }
        if ($column->primaryKey) {
            $definition .= ' PRIMARY KEY';
        }
        if ($column->nullable) {
            if ($column->autoIncrement || $column->primaryKey) {
                throw new \Exception('Columns with auto increment or primary key can not be set to nullable');
            }
            $definition .= ' NULL';
        } else {
            $definition .= ' NOT NULL';
        }
        return $definition;
    }
    public static function insert(array $data, string $tableName, #[\SensitiveParameter] string $dbname): string
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $query = "INSERT INTO `$dbname`.`$tableName` ($columns) VALUES ($placeholders);";
        return $query;
    }
    public static function update(array $data, string $tableName, #[\SensitiveParameter] string $dbName): string
    {
        $setClause = [];
        foreach ($data as $key => $value) {
            $setClause[] = "$key = :$key";
        }

        $setClause = implode(', ', $setClause);

        $query = "UPDATE `$dbName`.`$tableName` SET $setClause WHERE id = :id;";

        return $query;
    }
    public static function select(string $tableName, #[\SensitiveParameter] string $dbName, string $orderBy = null, array $columns = null, array $criteria = null, int $limit = null, int $offset = null): string
    {
        $columnsStr = '*';
        if ($columns !== null) {
            $columnsStr = implode(', ', $columns);
        }
        $query = "SELECT $columnsStr FROM `$dbName`.`$tableName`";

        if ($criteria !== null && !empty($criteria)) {
            $criteriaStr = self::selectFilter($criteria);
            $query .= " WHERE $criteriaStr";
        }

        if ($orderBy !== null) {
            $query .= " ORDER BY $orderBy";
        }

        if ($limit !== null) {
            $query .= " LIMIT $limit";
        }
        if ($offset !== null) {
            $query .= " OFFSET $offset";
        }

        $query .= ";";

        return $query;
    }
    public static function delete(string $tableName, #[\SensitiveParameter] string $dbName, array $criteria): string
    {
        $criteriaStr = self::selectFilter($criteria);

        $query = "DELETE FROM `$dbName`.`$tableName` WHERE $criteriaStr;";

        return $query;
    }
    /**
     * Creates a parametrized SQL filter e.g. 'column_name = :column_name' for sql injection security 
     *
     * @param  array $criteria syntax: ['column_name' 'operator' 'value'], only column_name will be used
     * @return string string ready to be concatenated with other parts of the SQL query
     */
    private static function selectFilter(array $criteria): string
    {
        $conditionStr = [];
        $conditionStr[] = "$criteria[0] $criteria[1] :$criteria[0]";

        return implode(' AND ', $conditionStr);
    }
}
