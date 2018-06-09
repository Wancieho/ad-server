<?php

use Jacwright\RestServer\RestException;

class MysqliDriver {

    static public $table = '';

    static public function get($id = null) {
        $data = [];

        if ($id !== null) {
            $statement = DB::handler()->prepare('SELECT * FROM ' . static::$table . ' WHERE id=' . $id);

            if ($statement) {
                $statement->execute();

                self::queryError($statement);

                $result = $statement->get_result();

                $data = $result->fetch_assoc();

                if (!is_null($data)) {
                    $statement->close();

                    unset($data['id']);

                    return (object) $data;
                }
            }

            return null;
        } else { // all entries for specified table
            $statement = DB::handler()->prepare('SELECT * FROM ' . static::$table);

            if ($statement) {
                $statement->execute();

                self::queryError($statement);

                $result = $statement->get_result();

                // insert all DB results into array for return
                while ($row = $result->fetch_assoc()) {
                    unset($row['id']);

                    $data[] = $row;
                }

                $statement->close();
            }

            return $data;
        }
    }

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

    static public function delete($id = null) {
        $statement = DB::handler()->prepare('DELETE FROM ' . static::$table . ' WHERE id=' . $id);

        $statement->execute();

        self::queryError($statement);

        $success = false;

        if ($statement->affected_rows > 0) {
            $success = true;
        }

        $statement->close();

        if ($success > 0) {
            return (object) ['id' => $id];
        }

        return (object) [];
    }

    static private function queryError($statement) {
        if (!empty($statement->error)) {
            throw new RestException(401, $statement->error);
        }
    }

}
