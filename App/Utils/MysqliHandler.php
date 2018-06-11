<?php

namespace App\Utils;

use Jacwright\RestServer\RestException;

/*
 * MySQLi class used for building queries to process using prepared statements
 */

class MysqliHandler {

    static public $table = '';

    /*
     * Creates an entry in the specified table
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

        // #TODO: add this check when running any queries
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
     * Reads either a single table row or multiple rows depending on params
     * 
     * @return mixed
     */

    static public function get($params = null) {
        $data = [];
        $where = isset($params->where) ? $params->where : [];
        $order = isset($params->order) ? ' ' . $params->order : '';
        $limit = isset($params->limit) ? ' ' . $params->limit : '';

        if (isset($params->id)) {
            // rebuild ID into object for dynamic where building
            $where = ['id' => $params->id];
        }

        $query = 'SELECT * FROM ' . static::$table . self::buildWhere($where) . $order . $limit;

        $statement = DB::handler()->prepare($query);

        if ($statement) {
            $statement->execute();

            self::queryError($statement);

            $result = $statement->get_result();

            // insert all DB results into array for return
            if ($result->num_rows > 1) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            } elseif ($result->num_rows === 1) {
                $data = (object) $result->fetch_assoc();
            } else {
                $data = null;
            }

            $statement->close();

            return $data;
        }

        return null;
    }

    /*
     * Update table row
     * 
     * @return object
     */

    static public function update($params = null) {
//        $query = 'INSERT INTO ' . static::$table . ' (' . join(',', $keys) . ') VALUES (' . join(',', $inserts) . ')';

        $fields = '';

        foreach ($params->fields as $key => $val) {
            
        }

        $query = 'UPDATE ' . static::$table . ' SET ' . $fields . ' WHERE id=' . $params->id;

        $statement = DB::handler()->prepare($query);
    }

    /*
     * Delete row from table
     * 
     * @return object
     */

    static public function delete($params = null) {
        $where = '';

        if (is_string($params->id)) {
            $where = ' WHERE id=' . $params->id;
        }

        $query = 'DELETE FROM ' . static::$table . $where;

        $statement = DB::handler()->prepare($query);

        $statement->execute();

        self::queryError($statement);

        $success = false;

        if ($statement->affected_rows > 0) {
            $success = true;
        }

        $statement->close();

        if ($success > 0 && is_string($params->id)) {
            return (object) ['id' => $params->id];
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
     * 
     * @return string
     */

    static private function buildWhere($params = []) {
        $where = '';

        foreach ($params as $key => $val) {
            if (empty($where)) {
                $where = ' WHERE ';
            } elseif (!empty($where)) {
                $where .= ' AND ';
            }

            $where .= $key . '=' . $val;
        }

        return $where;
    }

}
