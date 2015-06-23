<?php

namespace QueryPro\Handler;

use QueryPro\Socket;
use QueryPro\Utility;

/**
 * QueryPro Handler
 *
 * @package QueryPro
 * @author  Jesse Cascio <jessecascio@gmail.com>
 * @see     jessesnet.com
 */
class SQL
{	
	/**
	 * @var string
	 */
	private $table;

	/**
	 * @var resource
	 */
	private $socket;
	
	/**
	 * @var array
	 */
	private $batch = [];

	/**
	 * @param string
	 * @param resource
	 */
	public function __construct($table, Socket $socket)
	{
		$this->table  = preg_replace("/[^A-Za-z0-9\-]/", '', $table);
		$this->socket = $socket;
	}

	/**
	 * @return string
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->batch);
	}

	/**
	 * Queue up the requests
	 * @param string - sql query
	 * @param float  - microseconds
	 * @param float  - microseconds
	 */
	public function batch($q, $start=null, $end=null)
	{
		if (is_null($start) || is_null($end)) {
			$start = microtime(true);
			$end   = microtime(true);
		}

		$count = count($this->batch);
		$query = new Utility\Query($q);

		// verify is a query
		if (!$query->isQuery()) {
			return;
		}

		$d = (floatval($end) - floatval($start)) * 1000;

		// batch data
		$this->batch[$count] = [
			$query->getHash(),
			$query->getQuery(),		   
			floatval(number_format($d, 4, '.', '')),
			$query->isInsert() ? 1 : 0, // c
			$query->isSelect() ? 1 : 0, // r
			$query->isUpdate() ? 1 : 0, // u 
			$query->isDelete() ? 1 : 0  // d   
		];
	}

	/**
	 * Send the batch
	 */
	public function send()
	{
		// @todo Consider Exception handling for table length
		if (!count($this->batch) || !strlen($this->table)) {
			return;
		}

		$data = [
			'name'    => $this->table,
			'columns' => [
				'hash',
				'query',
				'duration',
				'insert', // c
				'select', // r
				'update', // u
				'delete'  // d
			],
			'points'  => $this->batch
		];

		$this->socket->write($data);

		$this->batch = [];
	}

}
