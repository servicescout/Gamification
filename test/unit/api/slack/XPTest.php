<?php

namespace Test\API\Slack;

use API\Slack\XP;
use Mockery as m;

class XPTest extends \Test\TestCase
{
  public function testProcess()
  {
    $auth = m::mock('Auth\Auth');
    $auth->shouldReceive('verify')->andReturn(true);

    $player = m::mock('Model\Entity\Player');
    $player->shouldReceive('getAttribute')->once()->with('id')->andReturn('fooPlayerId');

    $playerAccount = m::mock('Model\Entity\Account');
    $playerAccount->shouldReceive('getAttribute')->once()->with('player')->andReturn($player);

    $subResponse = m::mock('API\Response');
    $subResponse->shouldReceive('getStatus')->once()->andReturn(200);
    $subResponse->shouldReceive('getData')->once()->andReturn(array(
      'params' => array('message' => 'fooMessage'),
    ));

    $giveApi = m::mock('API\Event\GiveXP');
    $giveApi->shouldReceive('process')->once()->andReturn($subResponse);

    $factory = m::mock('API\Factory');
    $factory->shouldReceive('eventGiveXP')->once()->with($auth, json_encode(array(
      'playerId' => 'fooPlayerId',
      'amount' => 'fooAmount',
      'description' => 'fooDescription',
    )))->andReturn($giveApi);

    $retreiver = m::mock('Model\Retriever');
    $retreiver->shouldReceive('get->where->first')->once()->andReturn($playerAccount);

    $api = new XP($auth, array(
      'team_id' => 'fooTeamId',
      'user_id' => 'fooUserId',
      'text' => 'fooCommand fooRecipient fooAmount fooDescription',
    ), $factory, $retreiver);

    $response = $api->process();

    self::assertEquals('fooMessage, XP Awarded Successfully', $response->getData());
  }
}