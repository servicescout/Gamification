<?php

namespace Model\Entity;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
  protected $table = 'public.player';
  public function getDates() { return array(); }

  public function account()
  {
    return $this->belongsTo('Model\Entity\Account');
  }

  public function toArray()
  {
    $con = $this->getConnection();

    $stats = $con->selectOne(<<<SQL

SELECT
  (SELECT COALESCE(SUM(amount), 0)
  FROM event.xp_accrual
  WHERE player_id = :player) AS xp,
  (SELECT COALESCE(SUM(CASE WHEN to_player_id = :player THEN amount ELSE -amount END), 0)
  FROM event.gold_transfer
  WHERE (from_player_id = :player OR to_player_id = :player)) AS gold
;

SQL
      , array(
        'player' => $this->id,
      ));

    $helper = new \Game\Level\Helper();

    return array_merge(parent::toArray(), array(
      'account' => Account::find($this->account_id)->toArray(),
      'level' => $helper->getLevelForXP($stats['xp']),
      'xp' => $stats['xp'],
      'gold' => $stats['gold'],
      'nextLevel' => array(
        'percent' => $helper->getPercentToNextLevel($stats['xp']),
        'xp' => $helper->getXPNeededForNextLevel($stats['xp']),
      ),
    ));
  }
}