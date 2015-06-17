<?php

namespace QueryPro\Test;

use PHPUnit_Framework_TestCase;
use QueryPro\Socket;

class SocketTest extends PHPUnit_Framework_TestCase
{	
	private $host = '127.0.0.1';

	private $port = 4444;

	public function setUp()
	{
		$this->socket = new Socket($this->host, $this->port);
	}

	public function testGetHost()
	{
		$this->assertEquals($this->host, $this->socket->getHost());
	}

	public function testGetPort()
	{
		$this->assertEquals($this->port, $this->socket->getPort());
	}

	public function testGetSocket()
	{
		$this->assertTrue(is_resource($this->socket->getSocket()));
	}

	public function testProtocol()
	{
		$this->assertEquals(Socket::PROTOCOL, 'udp');
	}
}