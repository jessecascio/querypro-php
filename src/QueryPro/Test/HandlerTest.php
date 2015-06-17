<?php

namespace QueryPro\Test;

use PHPUnit_Framework_TestCase;
use QueryPro\Handler;

class HandlerTest extends PHPUnit_Framework_TestCase
{	
	private $socket;

	private $handler;

	public function setUp()
	{
		$this->socket  = \Phake::mock('QueryPro\Socket');
		$this->handler = new Handler($this->socket);
	}

	public function testSend()
	{
		$this->handler->send('name',[],[]);
		\Phake::verify($this->socket)->write(\Phake::anyParameters());
	}
}
