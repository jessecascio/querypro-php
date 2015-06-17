<?php

namespace QueryPro;

/**
 * QueryPro Handler
 *
 * @package QueryPro
 * @author  Jesse Cascio <jessecascio@gmail.com>
 * @see     jessesnet.com
 */
class Handler
{	
	/**
	 * @var resource
	 */
	private $socket;

	/**
	 * @param string
	 * @param int
	 */
	public function __construct(Socket $socket)
	{
		$this->socket = $socket;
	}

	/**
	 * @param string
	 * @param array
	 * @param array
	 */
	public function send($name, array $columns, array $points)
	{
		$data = [
			'name'    => $name,
			'columns' => $columns,
			'points'  => $points
		];

		$this->socket->write($data);
	}
}
