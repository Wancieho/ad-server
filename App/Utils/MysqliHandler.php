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
		$vals = [];
		$keys = [];
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
			// #TODO: move into reusable method
			throw new RestException(401, 'Error executing query `' . $query . '`');
		}

		// #TODO: put this into reusable method
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
		$where = [];
		$order = isset($params->order) ? ' ' . $params->order : '';
		$limit = isset($params->limit) ? ' ' . $params->limit : '';

		if (is_string($params) || is_integer($params)) {
			$where = (object) ['id' => (integer) $params];
		} elseif (isset($params->where) && is_array($params->where)) {
			$where = $params->where;
		} elseif (isset($params->id)) {
			$where = (object) ['id' => (integer) $params->id];
		}

		$query = 'SELECT * FROM ' . static::$table . self::buildWhere($where) . $order . $limit;

		$statement = DB::handler()->prepare($query);

		if (!$statement) {
			throw new RestException(401, 'Error executing query `' . $query . '`');
		}

		if ($statement) {
			$statement->execute();

			self::queryError($statement);

			$result = $statement->get_result();

			if (is_string($params) || is_integer($params) || isset($params->id)) {
				$data = (object) $result->fetch_assoc();
			} else {
				while ($row = $result->fetch_assoc()) {
					$data[] = $row;
				}
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

	static public function update($data = null) {
		$id = $data->id;
		$vals = [];
		$set = '';

		// remove ID form array to prevent any MySQL query issues
		unset($data->id);

		foreach ($data as $key => $val) {
			if (!is_null($val)) {
				if (!empty($set)) {
					$set .= ', ';
				}

				$set .= $key . '=?';
				$vals[] = $val;
			}
		}

		$query = 'UPDATE ' . static::$table . ' SET ' . $set . ' WHERE id=' . $id;

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

		$statement->close();

		return (object) ['id' => $id];
	}

	/*
	 * Delete row from table
	 * 
	 * @return object
	 */

	static public function delete($params = null) {
		$where = '';
		$id = null;

		if (is_int($params) || is_string($params)) {
			$id = (integer) $params;

			$where = ' WHERE id=' . $id;
		} elseif (isset($params->id)) {
			$id = (integer) $params->id;

			$where = ' WHERE id=' . $id;
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

		if ($success) {
			return true;
		}

		return false;
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
