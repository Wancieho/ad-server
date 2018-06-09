<?php

class Model {

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

    static private function run($action = '', $params = null, $order = '') {
        if (!isset(Config::$driver)) {
            throw new RestException(401, 'Specify Config data driver');
        }

        if (Config::$driver === 'file') {
            // #TODO: add file storage
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

    static private function storeName() {
        // if the store property has not been assigned then use the child class name
        if (empty(static::$store)) {
            static::$store = str_replace('model', '', strtolower(get_called_class()));
        }

        return static::$store;
    }

}
