<?php
include_once(sprintf("%s/../../../../wp-load.php", dirname(__FILE__)));

class WPSysObject
{
	public $id;

	public $dbVars;

	public function get_table()
	{
		global $wpdb;

		return $wpdb->prefix.$this->table;
	}

	protected function Load_dbVars()
	{
		if ($this->dbVars == null)
		{
			global $wpdb;

			$this->dbVars = $wpdb->get_results($wpdb->prepare('SELECT DISTINCT COLUMN_NAME, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = %s', $this->get_table()));
		}
	}

	public function Insert()
	{
		global $wpdb;

		$this->Load_dbVars();

		$data = array();
		$format = array();
		foreach($this->dbVars as $db_col)
		{
			$column_name = $db_col->COLUMN_NAME;
			if (($db_col->COLUMN_NAME != 'id') && ($this->$column_name != null))
			{
				switch(strtolower($db_col->DATA_TYPE))
				{
					case 'int':
					case 'tinyint':
					case 'smallint':
					case 'mediumint':
					case 'bigint':
					case 'bit':
						$format[] = '%d';
						break;

					case 'float':
					case 'double':
					case 'decimal':
						$format[] = '%f';
						break;

					default:
						$format[] = '%s';
						break;
				}

				$data[$column_name] = $this->$column_name;
			}
		}

		if ($wpdb->insert($this->get_table(), $data, $format) === false)
		{
			$this->id = null;
			throw new Exception($wpdb->last_error);
		}
		else
			$this->id = $wpdb->insert_id;
	}

	public function LoadBy_array($data)
	{
		foreach($data as $param => $value)
			$this->$param = $value;
	}

	public function Load($id)
	{
		global $wpdb;

		$result = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$this->get_table().' WHERE id = %d', $id));
		if ($result != null)
			$this->LoadBy_array($result);
		else
			throw new Exception('Registro não encontrado.');
	}

	public function Update()
	{
		global $wpdb;

		$this->Load_dbVars();

		$data = array();
		$format = array();
		foreach($this->dbVars as $db_col)
		{
			if ($db_col->COLUMN_NAME != 'id')
			{
				switch(strtolower($db_col->DATA_TYPE))
				{
					case 'int':
					case 'tinyint':
					case 'smallint':
					case 'mediumint':
					case 'bigint':
					case 'bit':
						$format[] = '%d';
						break;

					case 'float':
					case 'double':
					case 'decimal':
						$format[] = '%f';
						break;

					default:
						$format[] = '%s';
						break;
				}

				$column_name = $db_col->COLUMN_NAME;
				$data[$column_name] = $this->$column_name;
			}
		}

		$sql = 'UPDATE '.$this->get_table().' SET';

		$non_null_values = array();
		$sql_values = '';
		$i = 0;
		foreach($data as $key => $value) {
			$sql_values .= ','.$key.'=';
			if ($value !== null)
			{
				$sql_values .= $format[$i];
				$non_null_values[] = $value;
			}
			else
				$sql_values .= 'NULL';

			$i++;
		}

		$sql .= ' '.substr($sql_values, 1).' WHERE id = %d';
		$non_null_values[] = $this->id;

		array_unshift($non_null_values, $sql);
		if ($wpdb->query(call_user_func_array(array($wpdb, 'prepare'), $non_null_values)) === false)
			throw new Exception($wpdb->last_error);
	}

	public function Delete($ids = array())
	{
		global $wpdb;

		if (count($ids) == 0)
			$ids[0] = $this->id;

		$str_ids = '';
		foreach($ids as $id)
			$str_ids .= ','.intval($id);

		if ($wpdb->query('DELETE FROM '.$this->get_table().' WHERE id IN ('.substr($str_ids, 1).')') === false)
			throw new Exception($wpdb->last_error);
	}
}
?>