<?php
/**
 * ZfHipsters Key Value Storage Adapter (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/key-value-storage for the canonical source repository
 * @copyright Copyright 2014 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace ZfHipsters\KeyValueStorage\Options;
use Zend\Cache\Storage\Adapter\AdapterOptions as ZendAdapterOptions;
use Zend\Db\Adapter\Adapter;

/**
 * Class AdapterOptions
 * @package ZfHipsters\KeyValueStorage\Options
 */
class AdapterOptions extends ZendAdapterOptions
{
    /**
     * @var array
     */
    protected $connection;
    /**
     * @var object
     */
    protected $table;
    /**
     * @var adapter
     */
    protected $db;

    /**
     * Set connection
     * @param mixed $connection
     * @return $this
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
        $this->setDb($connection);
        return $this;
    }

    /**
     * Get connection
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Set table
     * @param mixed $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = (object) $table;
        return $this;
    }

    /**
     * Get table
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set db
     * @param array $connection
     * @return $this
     */
    public function setDb(array $connection)
    {
        if (is_null($this->db)) {
            $this->db = new Adapter($connection);
        }

        return $this;
    }

    /**
     * @return Adapter
     */
    public function getDb()
    {
        return $this->db;
    }
}
