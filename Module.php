<?php
/**
 * ZfHipsters Key Value Storage Adapter (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/key-value-storage for the canonical source repository
 * @copyright Copyright 2014 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace ZfHipsters\KeyValueStorage;

/**
 * Class Module
 * @package Connector
 */
class Module
{
    public function getConfig()
    {
        $config = include __DIR__ . '/config/module.config.php';
        if (empty($config['zf-hipsters']['keyvalue']['adapter']['name'])) {
            throw new \Exception('This module is not correctly configured. Please see readme file.');
        }

        $adapterName = $config['zf-hipsters']['keyvalue']['adapter']['name'];

        return array_merge($config, array(
            'service_manager' => array(
                'factories' => array(
                    $adapterName => 'ZfHipsters\KeyValueStorage\Factory\AdapterFactory',
                )
            )
        ));
    }

    /**
     * Setup Autoloader
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array('Zend\Loader\StandardAutoloader' => array('namespaces' => array(
            __NAMESPACE__ => __DIR__ . '/src/',
        )));
    }
}
