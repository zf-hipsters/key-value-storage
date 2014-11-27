<?php
/**
 * ZfHipsters Key Value Storage Adapter (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/key-value-storage for the canonical source repository
 * @copyright Copyright 2014 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace KeyValueStorage\Adapter;

use Zend\Cache\Exception;
use Zend\Cache\Storage\Adapter\AbstractAdapter;
use KeyValueStorage\Options\AdapterOptions;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

/**
 * Class StorageAdapter
 * @package KeyValueStorage\Adapter
 */
class StorageAdapter extends AbstractAdapter
{

    /**
     * @var bool
     */
    protected $initialized = false;

    /**
     * @param null $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        // reset initialized flag on update option(s)
        $initialized = & $this->initialized;
        $this->getEventManager()->attach('option', function ($event) use (& $initialized) {
            $initialized = false;
        });
    }

    /**
     * Set Options
     * @param array|\Traversable|\Zend\Cache\Storage\Adapter\AdapterOptions $options
     * @return AbstractAdapter
     */
    public function setOptions($options)
    {
        if (!$options instanceof AdapterOptions) {
            $options = new AdapterOptions($options);
        }
        return parent::setOptions($options);
    }

    /**
     * Get Options
     * @return mixed
     */
    public function getOptions()
    {
        if (!$this->options) {
            $this->setOptions(new AdapterOptions());
        }
        return $this->options;
    }

    /**
     * Get item by key
     * @param string $normalizedKey
     * @param null $success
     * @param null $casToken
     * @return mixed
     */
    protected function internalGetItem(& $normalizedKey, & $success = null, & $casToken = null)
    {
        $tableOptions = $this->getOptions()->getTable();

        $sql = new Sql($this->getAdapter());
        $select = $sql->select()
            ->from($tableOptions->name)
            ->columns([$tableOptions->mapping['value']])
            ->where([$tableOptions->mapping['key'] => $this->getKey($normalizedKey)]);

        $stmt = $sql->prepareStatementForSqlObject($select);

        $result = $stmt->execute()->current();

        $success = true;
        $casToken = json_decode($result['value'], 1);
        return json_decode($result['value'], 1);
    }

    /**
     * Get items by key array
     * @param array $normalizedKeys
     * @return array
     */
    protected function internalGetItems(array & $normalizedKeys)
    {
        foreach ($normalizedKeys as &$key) {
            $key = $this->getKey($key);
        }

        $tableOptions = $this->getOptions()->getTable();

        $sql = new Sql($this->getAdapter());
        $select = $sql->select()
            ->from($tableOptions->name)
            ->columns([$tableOptions->mapping['value']])
            ->where([$tableOptions->mapping['key'] => $normalizedKeys]);

        $stmt = $sql->prepareStatementForSqlObject($select);

        $results = $stmt->execute();
        $return = [];
        foreach ($results as $result) {
            $return[] = json_decode($result['value'], 1);
        }

        return $return;
    }

    /**
     * Has item by key
     * @param string $normalizedKey
     * @return int
     */
    protected function internalHasItem(& $normalizedKey)
    {

        $tableOptions = $this->getOptions()->getTable();

        $sql = new Sql($this->getAdapter());
        $select = $sql->select()
            ->from($tableOptions->name)
            ->columns([$tableOptions->mapping['value']])
            ->where([$tableOptions->mapping['key'] => $this->getKey($normalizedKey)]);

        $stmt = $sql->prepareStatementForSqlObject($select);
        return $stmt->execute()->getAffectedRows();
    }

    /**
     * Set item by key
     * @param string $normalizedKey
     * @param mixed $value
     * @return bool
     */
    protected function internalSetItem(& $normalizedKey, & $value)
    {
        $tableOptions = $this->getOptions()->getTable();
        $sql = new Sql($this->getAdapter());

        if ($this->internalHasItem($normalizedKey)) {
            $query = $sql->update()
                ->table($tableOptions->name)
                ->set([$tableOptions->mapping['value'] => $value])
                ->where([$tableOptions->mapping['key'] => $this->getKey($normalizedKey)]);
        } else {
            $query = $sql->insert()
                ->into($tableOptions->name)
                ->values([$tableOptions->mapping['key'] => $value]);
        }

        $stmt = $sql->prepareStatementForSqlObject($query);
        return $stmt->execute()->valid();
    }

    /**
     * Add item by key
     * @param string $normalizedKey
     * @param mixed $value
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    protected function internalAddItem(& $normalizedKey, & $value)
    {
        $tableOptions = $this->getOptions()->getTable();

        $sql = new Sql($this->getAdapter());
        $insert = $sql->insert()
            ->into($tableOptions->name)
            ->values([$tableOptions->mapping['key'] => $value]);

        $stmt = $sql->prepareStatementForSqlObject($insert);
        return $stmt->execute();
    }

    /**
     * Remove item by key
     * @param string $normalizedKey
     * @return bool
     */
    protected function internalRemoveItem(& $normalizedKey)
    {
        $tableOptions = $this->getOptions()->getTable();

        $sql = new Sql($this->getAdapter());
        $delete = $sql->delete()
            ->from($tableOptions->name)
            ->where([$tableOptions->mapping['key'] => $this->getKey($normalizedKey)]);

        $stmt = $sql->prepareStatementForSqlObject($delete);
        return (bool) $stmt->execute();
    }

    /**
     * Add namespace to key
     * @param $normalizedKey
     * @return string
     */
    protected function getKey($normalizedKey)
    {
        return $this->getOptions()->getNamespace() . $normalizedKey;
    }

    /**
     * Get Zend db adapter
     * @return Adapter
     */
    protected function getAdapter()
    {
        return $this->getOptions()->getDb();
    }
}
