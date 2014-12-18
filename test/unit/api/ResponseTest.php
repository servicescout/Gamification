<?php

namespace Test\API;

use API\Response;

class ResponseTest extends \Test\TestCase
{
  public function testGetSetStatus()
  {
    $r = new Response();

    self::assertEquals(200, $r->getStatus());

    $r->setStatus('foo');
    self::assertEquals('foo', $r->getStatus());
  }

  public function testGetSetData()
  {
    $r = new Response();

    self::assertEquals(array(), $r->getData());

    $r->setData('foo');
    self::assertEquals('foo', $r->getData());
  }
}