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
    protected Safer $safer;
    protected Mysql $mysql;
    
    /**
     * Method __construct
     *
     * @param Safer $safer safer
     * @param Mysql $mysql mysql
     */
    public function __construct(Safer $safer, Mysql $mysql)
    {
        $this->safer = $safer;
        $this->mysql = $mysql;
    }
    
    /**
     * Method row
     *
     * @param string   $tableUnsafe  tableUnsafe
     * @param string[] $fieldsUnsafe fieldsUnsafe
     * @param string[] $filterUnsafe filterUnsafe
     *
     * @return Assoc
     */
    public function row(
        string $tableUnsafe,
        array $fieldsUnsafe = ['*'],
        array $filterUnsafe = []
    ): Assoc {
        $table = $this->mysql->escape($tableUnsafe);
        $fields = implode(
            ', ',
            $this->safer->freez([$this->mysql, 'escape'], $fieldsUnsafe)
        );
        $filter = $this->safer->freez([$this->mysql, 'escape'], $filterUnsafe);
        
        $query = "SELECT $fields FROM $table";
        
        if ($filter) {
            $conds = [];
            foreach ($filter as $key => $value) {
                $conds[] = "$key = '$value'";
            }
            $where = implode(' OR ', $conds);
            $query .= " WHERE $where";
        }
        
        $query .= " LIMIT 1";
        
        return $this->mysql->selectOne($query);
    }
}
