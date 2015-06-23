<?php

namespace QueryPro\Handler;

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
	 * Send the batch
	 */
	public function send()
	{
		if (!count($this->batch)) {
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

	/**
	 * Queue up the requests
	 * @param string - sql query
	 * @param float 
	 */
	public function batch($q, $duration)
	{
		$count = count($this->batch);
		$query = new Utility\Query($q);

		// verify is a query
		if (!$query->isQuery()) {
			return;
		}

		// bacth data
		$this->batch[$count] = [
			$query->getHash(),
			$query->getQuery(),		   
			floatval($duration),
			$query->isInsert() ? 1 : 0, // c
			$query->isSelect() ? 1 : 0, // r
			$query->isUpdate() ? 1 : 0, // u 
			$query->isDelete() ? 1 : 0  // d   
		];
	}

}
