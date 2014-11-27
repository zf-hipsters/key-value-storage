<?php
/**
 * ZfHipsters Key Value Storage Adapter (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/key-value-storage for the canonical source repository
 * @copyright Copyright 2014 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace KeyValueStorage\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Cache\StorageFactory;

class AdapterFactory implements FactoryInterface
{
    /**
     * Create Service Factory
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Zend\Cache\Storage\StorageInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $storageOptions = $config['zf-hipsters']['keyvalue'];

        $storageName = (!empty($storageOptions['adapter']['name'])) ? $storageOptions['adapter']['name'] : 'keyvalue';

        StorageFactory::getAdapterPluginManager()->setInvokableClass(
            $storageName,
            'KeyValueStorage\Adapter\StorageAdapter'
        );

        $cache  = StorageFactory::factory($storageOptions);
        return $cache;
    }
}
