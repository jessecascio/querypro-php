<?php

namespace Test\QueryPro\Utility;

use PHPUnit_Framework_TestCase;
use QueryPro\Utility\Query;

class QueryTest extends PHPUnit_Framework_TestCase
{	

	public function testGetQuery()
	{
		$s = "SELECT * FROM EVERYWHERE";
		$q = new Query($s);
		$this->assertEquals($q->getQuery(), $s);
	}

	public function testQueryTrim()
	{
		$s = "    SELECT * FROM EVERYWHERE    ";
		$q = new Query($s);
		$this->assertEquals($q->getQuery(), "SELECT * FROM EVERYWHERE");
	}

	public function testGetHash()
	{
		$q = new Query('adsasd');
		$this->assertTrue($q->getHash() !== null);
	}

	public function testIsQuery()
	{
		$q = new Query('adsasd');
		$this->assertTrue($q->isQuery());

		$q = new Query('8329043');
		$this->assertTrue($q->isQuery());

		$q = new Query('@&(*#@');
		$this->assertFalse($q->isQuery());
	}

	public function testIsSelect()
	{
		$s = 'SELECT * FROM BLAG';
		$q = new Query($s);
		$this->assertTrue($q->isSelect());

		$s = '     SeleCT    * FROM BLAG';
		$q = new Query($s);
		$this->assertTrue($q->isSelect());
		
		$s = 'ELECT * FROM BLAG';
		$q = new Query($s);
		$this->assertFalse($q->isSelect());
	}

	public function testIsUpdate()
	{
		$s = 'UPDATE * FROM BLAG';
		$q = new Query($s);
		$this->assertTrue($q->isUpdate());

		$s = '     upDATE    * FROM BLAG';
		$q = new Query($s);
		$this->assertTrue($q->isUpdate());
		
		$s = 'Pdate * FROM BLAG';
		$q = new Query($s);
		$this->assertFalse($q->isUpdate());
	}

	public function testIsDelete()
	{
		$s = 'DELETE * FROM BLAG';
		$q = new Query($s);
		$this->assertTrue($q->isDelete());

		$s = '     dELEte    * FROM BLAG';
		$q = new Query($s);
		$this->assertTrue($q->isDelete());
		
		$s = 'elete * FROM BLAG';
		$q = new Query($s);
		$this->assertFalse($q->isDelete());
	}

	public function testIsInsert()
	{
		$s = 'INSERT * FROM BLAG';
		$q = new Query($s);
		$this->assertTrue($q->isInsert());

		$s = '     InsERT    * FROM BLAG';
		$q = new Query($s);
		$this->assertTrue($q->isInsert());
		
		$s = 'isert * FROM BLAG';
		$q = new Query($s);
		$this->assertFalse($q->isInsert());
	}
}
