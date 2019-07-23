<?php 

namespace app\models;

use app\core\Model;
use app\core\Controller;

/**
 * Main Model
 */
class Main extends Model
{
	public $error;

	
	public function setHistoryDb($value, $event, $type, $date, $description = "")
	{
		$params = [
			'id' => NULL,
			'event' => $event,
			'value' => serialize($value),
			'type' => $type,
			'dates' => $date,
			'description' => $description,
		];
		$status = $this->db->query('INSERT INTO history VALUES (:id, :event, :value, :type, :dates, :description)', $params);
	}


	public function getHistoryDb($route, $count_per_page = 10)
	{
		$max = $count_per_page;
		$params = [
			'max' => $max,
			'start' => ((($route['page'] ?? 1) - 1) * $max),
		];

		$history = $this->db->row('SELECT * FROM history ORDER BY id DESC LIMIT :start, :max', $params);
		foreach ($history as $key => $val) {
			$data[$key]['event'] = $val['event'];
			$data[$key]['value'] = unserialize($val['value']);
			$data[$key]['type'] = $val['type'];
			$data[$key]['dates'] = $val['dates'];
			$data[$key]['description'] = $val['description'];
		}
		return $data;
	}

	public function historyCount()
	{
		return $this->db->column('SELECT COUNT(id) FROM history');
	}

}