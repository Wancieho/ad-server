<?php

namespace App\Utils;

use Jacwright\RestServer\RestException;

class MysqliDriver {

    static public $table = '';

    /*
     * Retrieves either a single table row or multiple rows depending on params
     * 
     * @return mixed
     */

    static public function get($params = null, $order = '') {
        $data = [];

        // #TODO: improve below so that single entries and lists can be retrieved using WHERE and ORDER
        if ($params !== null) {
            $query = 'SELECT * FROM ' . static::$table . ' WHERE ' . self::buildWhere($params) . ' ' . $order;

            $statement = DB::handler()->prepare($query);

            if ($statement) {
                $statement->execute();

                self::queryError($statement);

                $result = $statement->get_result();

                $data = $result->fetch_assoc();

                if (!is_null($data)) {
                    $statement->close();

                    return (object) $data;
                }
            }

            return (object) [];
        } else { // all entries for specified table
            $statement = DB::handler()->prepare('SELECT * FROM ' . static::$table);

            if ($statement) {
                $statement->execute();

                self::queryError($statement);

                $result = $statement->get_result();

                // insert all DB results into array for return
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                $statement->close();
            }

            return $data;
        }
    }

    /*
     * Saves data into table
     * 
     * @return object
     */

    static public function save($data = null) {
        $keys = [];
        $vals = [];
        $inserts = [];

        foreach ($data as $key => $val) {
            if (!is_null($val)) {
                $keys[] = $key;
                $vals[] = $val;
                $inserts[] = '?';
            }
        }

        $query = 'INSERT INTO ' . static::$table . ' (' . join(',', $keys) . ') VALUES (' . join(',', $inserts) . ')';

        $statement = DB::handler()->prepare($query);

        if (!$statement) {
            throw new RestException(401, 'Error executing query `' . $query . '`');
        }

        $dataTypes = '';

        foreach ($data as $key => $val) {
            if (is_string($val)) {
                $dataTypes .= 's';
            } elseif (is_int($val)) {
                $dataTypes .= 'i';
            }
        }

        $params = [];

        // add data types to bind params array
        $params[] = &$dataTypes;

        // add values to bind params array
        foreach ($vals as $key => $val) {
            $params[] = &$vals[$key];
        }

        // dynamically call bind_param method based on array of fields to save
        call_user_func_array(array($statement, 'bind_param'), $params);

        $statement->execute();

        self::queryError($statement);

        // store inserted ID for return
        $id = $statement->insert_id;

        $statement->close();

        return (object) ['id' => $id];
    }

    /*
     * Delete row from table
     * 
     * @return object
     */

    static public function delete($id = null) {
        $where = '';

        if (is_string($id)) {
            $where = ' WHERE id=' . $id;
        }

        $statement = DB::handler()->prepare('DELETE FROM ' . static::$table . $where);

        $statement->execute();

        self::queryError($statement);

        $success = false;

        if ($statement->affected_rows > 0) {
            $success = true;
        }

        $statement->close();

        if ($success > 0 && is_string($id)) {
            return (object) ['id' => $id];
        }

        return (object) [];
    }

    /*
     * Reusable query error method
     * 
     * @return string
     */

    static private function queryError($statement) {
        if (!empty($statement->error)) {
            throw new RestException(401, $statement->error);
        }
    }

    /*
     * Where claus Query string builder
     */

    static private function buildWhere($params = null) {
        $where = '';

        if (!is_array($params)) {
            $where = 'id=' . $params;
        } elseif (is_array($params)) {
            foreach ($params as $key => $val) {
                if (!empty($where)) {
                    $where .= ' AND ';
                }

                $where .= $key . '=' . $val;
            }
        }

        return $where;
    }

}
