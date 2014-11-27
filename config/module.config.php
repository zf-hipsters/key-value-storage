<?php
/**
 * ZfHipsters Key Value Storage Adapter (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/key-value-storage for the canonical source repository
 * @copyright Copyright 2014 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
return array(
    'zf-hipsters' => array(
        'keyvalue' => array(
            'adapter' => array(
                'name'     =>'couchbase',
                'options'  => array(
                    'namespace'  => 'shop',
                    'connection' => array(
                        'driver' => 'Pdo',
                        'dsn' => 'mysql:dbname=Alice;host=localhost',
                        'username' => 'root',
                        'password' => '',
                    ),
                    'table' => array(
                        'name' => 'keyvalue',
                        'mapping' => array(
                            'key' => 'key',
                            'value' => 'value'
                        ),
                    ),
                ),
            ),
            'plugins' => array(
                'exception_handler' => array(
                    'throw_exceptions' => false
                ),
            ),
        ),
    ),
);
