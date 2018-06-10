<?php

namespace App\Utils;

use Config;

/*
 * Model class that custom models use to query DB or save to file system
 */

class Storage {
    /*
     * Property used for either MySQL table name or file storage file name
     */

    static protected $store = '';

    static public function get($params = null, $order = '') {
        return self::run('get', $params, $order);
    }

    static public function save($data = null) {
        return self::run('save', $data);
    }

    static public function delete($id = null) {
        return self::run('delete', $id);
    }

    /*
     * Switching method for reading either from file or MySQL specified by Config driver
     * 
     * @return mixed
     */

    static private function run($action = '', $params = null, $order = '') {
        if (!isset(Config::$driver)) {
            throw new RestException(401, 'Specify Config data driver');
        }

        // #TODO: add file storage
        if (Config::$driver === 'file') {
            
        } elseif (Config::$driver === 'mysqli') {
            MysqliDriver::$table = self::storeName();

            switch ($action) {
                case 'get':
                    return MysqliDriver::get($params, $order);

                case 'save':
                    return MysqliDriver::save($params);

                case 'delete':
                    return MysqliDriver::delete($params);
            }
        }

        throw new RestException(401, 'Specified Config data driver not supported');
    }

    /*
     * Generate dynamic store name based on child class
     * 
     * @return string
     */

    static private function storeName() {
        if (empty(static::$store)) {
            static::$store = str_replace('model', '', strtolower(str_replace('App\\Models\\', '', get_called_class())));
        }

        return static::$store;
    }

}
