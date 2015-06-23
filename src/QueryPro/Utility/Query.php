<?php

namespace QueryPro\Utility;

/**
 * Query Object
 *
 * @package QueryPro
 * @author  Jesse Cascio <jessecascio@gmail.com>
 * @see     jessesnet.com
 */
class Query
{	
	/**
	 * @var string
	 */
	private $query;

	/**
	 * @var bool
	 */
	private $isQuery  = true;

	/**
	 * @var bool
	 */
	private $isSelect = false;

	/**
	 * @var bool
	 */
	private $isInsert = false;
	
	/**
	 * @var bool
	 */
	private $isDelete = false;
	
	/**
	 * @var bool
	 */
	private $isUpdate = false;
	
	/**
	 * @param string
	 */
	public function __construct($query)
	{
		$this->query = trim($query);
		$this->parseQuery();
	}

	/**
	 * Determine query info
	 */
	private function parseQuery()
	{
		$q = strtolower(preg_replace("/[^A-Za-z0-9]/", '', $this->query)); 

		if (strlen($q) == 0) { // update to check actual queries
			$this->isQuery = false;
			return;
		}

		/**
		 * determine query type
		 * @todo account for select/insert
		 */
		if (substr($q, 0, 6) === 'select') {
			$this->isSelect = true;
		} else if (substr($q, 0, 6) === 'insert') {
			$this->isInsert = true;
		} else if (substr($q, 0, 6) === 'delete') {
			$this->isDelete = true;
		} else if (substr($q, 0, 6) === 'update') {
			$this->isUpdate = true;
		}
	}

	/**
	 * @return string
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * @return string
	 */
	public function getHash()
	{
		return crc32($this->query);
	}

	/**
	 * @return bool
	 */
	public function isQuery()
	{
		return $this->isQuery;
	}

	/**
	 * @return bool
	 */
	public function isSelect()
	{
		return $this->isSelect;
	}

	/**
	 * @return bool
	 */
	public function isUpdate()
	{
		return $this->isUpdate;
	}

	/**
	 * @return bool
	 */
	public function isDelete()
	{
		return $this->isDelete;
	}

	/**
	 * @return bool
	 */
	public function isInsert()
	{
		return $this->isInsert;
	}
}
