<?php

namespace Test\QueryPro\Handler;

use PHPUnit_Framework_TestCase;
use QueryPro\Handler;

class SQLTest extends PHPUnit_Framework_TestCase
{	
	private $socket;

	public function setUp()
	{
		$this->socket  = \Phake::mock('QueryPro\Socket');
	}

	public function testTableClean()
	{
		$h = new Handler\SQL('test-table!A& ds', $this->socket);
		$this->assertEquals('test-tableAds', $h->getTable());
	}

	public function testBatch()
	{
		$h = new Handler\SQL('test-table', $this->socket);
		$h->batch('query1', microtime(true), microtime(true));
		$h->batch('query2', microtime(true), microtime(true));

		$this->assertEquals($h->count(), 2);
	}

	public function testSend()
	{
		$h = new Handler\SQL('test-table', $this->socket);
		$h->batch('query1', microtime(true), microtime(true));
		$h->batch('query2', microtime(true), microtime(true));
		$h->send();

		\Phake::verify($this->socket, \Phake::times(1))->write(\Phake::anyParameters());
	}

	public function testBatchReset()
	{
		$h = new Handler\SQL('test-table', $this->socket);
		$h->batch('query1', microtime(true), microtime(true));
		$h->batch('query2', microtime(true), microtime(true));
		$h->send();

		$this->assertEquals($h->count(), 0);
	}

	public function testEmptyBatch()
	{
		$h = new Handler\SQL('test-table', $this->socket);
		$h->send();
		
		\Phake::verify($this->socket, \Phake::never())->write(\Phake::anyParameters());
	}

	public function testEmptyQuery()
	{
		$h = new Handler\SQL('test-table', $this->socket);
		$h->batch('', microtime(true), microtime(true));
		$h->send();
		
		\Phake::verify($this->socket, \Phake::never())->write(\Phake::anyParameters());
	}

	public function testEmptyTable()
	{
		$h = new Handler\SQL('', $this->socket);
		$h->batch('', microtime(true), microtime(true));
		$h->send();
		
		\Phake::verify($this->socket, \Phake::never())->write(\Phake::anyParameters());
	}
}
