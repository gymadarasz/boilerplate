<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library;

use RuntimeException;

/**
 * Crud
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Crud
{
    const LOGICS = ['AND', 'OR'];
    
    protected Safer $safer;
    protected Mysql $mysql;
    protected Session $session;

    /**
     * Method __construct
     *
     * @param Safer   $safer   safer
     * @param Mysql   $mysql   mysql
     * @param Session $session session
     */
    public function __construct(Safer $safer, Mysql $mysql, Session $session)
    {
        $this->safer = $safer;
        $this->mysql = $mysql;
        $this->session = $session;
    }
    
    /**
     * Method row
     *
     * @param string   $tableUnsafe  tableUnsafe
     * @param string[] $fieldsUnsafe fieldsUnsafe
     * @param string[] $filterUnsafe filterUnsafe
     * @param int      $limit        limit
     * @param int      $offset       offset
     * @param int      $uid          uid
     *
     * @return mixed
     */
    public function get(
        string $tableUnsafe,
        array $fieldsUnsafe,
        array $filterUnsafe = [],
        int $limit = 1,
        int $offset = 0,
        int $uid = 0
    ) {
        $table = $this->mysql->escape($tableUnsafe);
        $mysql = $this->mysql;
        $fields = implode(
            ', ',
            $this->safer->freez(
                static function ($value) use ($mysql, $table) {
                    return "`$table`.`" . $mysql->escape($value) . "`";
                },
                $fieldsUnsafe
            )
        );
        $query = "SELECT $fields FROM `$table`";
        
        if ($uid > -1) {
            if (!$uid) {
                $uid = (int)$this->session->get('uid');
            }
            $query .= " JOIN `ownership` "
                . "ON `ownership`.`row_id` = `$table`.`id` "
                . "AND `ownership`.`table_name` = '$table' "
                . "AND `ownership`.`user_id` = $uid";
        }
        
        $query .= $this->getWhere($filterUnsafe, 'OR');
        if ($limit >= 1) {
            $query .= " LIMIT $offset, $limit";
        }
        if ($limit === 1) {
            return $this->mysql->selectOne($query);
        }
        return $this->mysql->select($query);
    }
    
    /**
     * Method getWhere
     *
     * @param string[] $filterUnsafe filterUnsafe
     * @param string   $logic        logic
     *
     * @return string
     * @throws RuntimeException
     */
    protected function getWhere(array $filterUnsafe, string $logic): string
    {
        $filter = $this->safer->freez([$this->mysql, 'escape'], $filterUnsafe);
        if (!in_array($logic, self::LOGICS, true)) {
            throw new RuntimeException("Invalid logic: '$logic'");
        }
        $query = '';
        if ($filter) {
            $conds = [];
            foreach ($filter as $key => $value) {
                $conds[] = "`$key` = '$value'";
            }
            $where = implode(" $logic ", $conds);
            $query .= " WHERE $where";
        }
        return $query;
    }
    
    /**
     * Method add
     *
     * @param string   $tableUnsafe  tableUnsafe
     * @param string[] $valuesUnsafe valuesUnsafe
     *
     * @return int
     */
    public function add(string $tableUnsafe, array $valuesUnsafe): int
    {
        $table = $this->mysql->escape($tableUnsafe);
        $fields = $this->safer->freez([$this->mysql, 'escape'], $valuesUnsafe);
        $keys = implode('`, `', array_keys($fields));
        $values = implode("', '", $fields);
        $query = "INSERT INTO `$table` (`$keys`) VALUES ('$values')";
        return $this->mysql->insert($query);
    }
    
    /**
     * Method del
     *
     * @param string   $tableUnsafe  tableUnsafe
     * @param string[] $filterUnsafe filterUnsafe
     *
     * @return int
     */
    public function del(string $tableUnsafe, array $filterUnsafe): int
    {
        $table = $this->mysql->escape($tableUnsafe);
        $query = "DELETE FROM `$table`";
        $query .= $this->getWhere($filterUnsafe, 'AND');
        $query .= " LIMIT 1";
        return $this->mysql->delete($query);
    }
    
    /**
     * Method set
     *
     * @param string   $tableUnsafe  tableUnsafe
     * @param string[] $valuesUnsafe valuesUnsafe
     * @param string[] $filterUnsafe filterUnsafe
     *
     * @return int
     */
    public function set(
        string $tableUnsafe,
        array $valuesUnsafe,
        array $filterUnsafe
    ): int {
        $table = $this->mysql->escape($tableUnsafe);
        $fields = $this->safer->freez([$this->mysql, 'escape'], $valuesUnsafe);
        $sets = [];
        foreach ($fields as $key => $value) {
            $sets[] = "`$key` = '$value'";
        }
        $setstr = implode(', ', $sets);
        $where = $this->getWhere($filterUnsafe, 'AND');
        $query = "UPDATE $table SET $setstr $where LIMIT 1";
        return $this->mysql->update($query);
    }
}
