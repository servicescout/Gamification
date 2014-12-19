<?php

namespace Test\API\Slack;

use Mockery as m;

class SlackTest extends \Test\TestCase
{
  public function testParseUsername_everyone()
  {
    $players = array(
      $this->makePlayer('foo'),
      $this->makePlayer('bar'),
      $this->makePlayer('baz'),
    );

    $auth = m::mock('Auth\Auth');
    $requestArray = array();
    $apiFactory = m::mock('API\Factory');

    $retriever = m::mock('Model\Retriever');
    $retriever->shouldReceive('get->get->all')->once()->andReturn($players);

    $api = m::mock('API\Slack\Slack', array(
      $auth,
      $requestArray,
      $apiFactory,
      $retriever,
    ))->makePartial();

    self::assertEquals(array(
      'foo',
      'bar',
      'baz',
    ), $api->parseUsername('*', m::mock('API\Response')));
  }

  public function testParseUsername_guild()
  {
    $players = array(
      $this->makePlayer('foo'),
      $this->makePlayer('bar'),
      $this->makePlayer('baz'),
    );

    $auth = m::mock('Auth\Auth');
    $requestArray = array();
    $apiFactory = m::mock('API\Factory');

    $retriever = m::mock('Model\Retriever');
    $retriever->shouldReceive('get->select->join->where->get->all')->once()->andReturn($players);

    $api = m::mock('API\Slack\Slack', array(
      $auth,
      $requestArray,
      $apiFactory,
      $retriever,
    ))->makePartial();

    self::assertEquals(array(
      'foo',
      'bar',
      'baz',
    ), $api->parseUsername('guild:fooGuild', m::mock('API\Response')));
  }

  private function makePlayer($id)
  {
    $p = m::mock('Model\Entity\Player');
    $p->shouldReceive('getAttribute')->once()->with('id')->andReturn($id);

    return $p;
  }
}