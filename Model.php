<?php

class Model {

    static protected $store = '';

    static public function get($id = null) {
        return self::run('get', $id);
    }

    static public function save($data = null) {
        return self::run('save', $data);
    }

    static public function delete($id = null) {
        return self::run('delete', $id);
    }

    static private function run($action = '', $param = null) {
        if (!isset(Config::$driver)) {
            throw new RestException(401, 'Specify Config data driver');
        }

        if (Config::$driver === 'file') {
            // #TODO: add file storage
        } elseif (Config::$driver === 'mysqli') {
            MysqliDriver::$table = self::storeName();

            switch ($action) {
                case 'get':
                    return MysqliDriver::get($param);

                case 'save':
                    return MysqliDriver::save($param);

                case 'delete':
                    return MysqliDriver::delete($param);
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
