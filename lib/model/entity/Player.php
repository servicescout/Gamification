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

  public function getCurrentLevel()
  {
    $stats = $this->getStatsArray();
    $helper = new \Game\Level\Helper();

    return $helper->getLevelForXP($stats['xp']);
  }

  public function getGold()
  {
    $stats = $this->getStatsArray();

    return $stats['gold'];
  }

  public function toArray()
  {
    $config = \Util\Config::get();

    return array_merge(parent::toArray(), array(
      'account' => Account::find($this->account_id)->toArray(),
      'avatar' => (!is_null($this->avatar))
        ? $config->getValue('baseUrl') . $this->avatar
        : null,
    ), $this->getStatsArray());
  }

  public function getStatsArray()
  {
    $con = $this->getConnection();

    // calculate the start of the quarter
    $year = date('Y');
    $month = (floor((date('n') - 1) / 3) * 3) + 1;
    $day = '01';
    $startOfQuarter = "$year-$month-$day";

    $stats = $con->selectOne(<<<SQL

SELECT
  (
    SELECT COALESCE(SUM(amount), 0)
    FROM event.xp_accrual
    WHERE player_id = :player
      AND created_at >= :startDate
  ) AS xp,
  (SELECT COALESCE(SUM(CASE WHEN to_player_id = :player THEN amount ELSE -amount END), 0)
  FROM event.gold_transfer
  WHERE (from_player_id = :player OR to_player_id = :player)) AS gold
;

SQL
      , array(
        'player' => $this->id,
        'startDate' => $startOfQuarter,
      ));

    $helper = new \Game\Level\Helper();

    return array_merge($stats, array(
      'level' => $helper->getLevelForXP($stats['xp']),
      'nextLevel' => array(
        'percent' => $helper->getPercentToNextLevel($stats['xp']),
        'xp' => $helper->getXPNeededForNextLevel($stats['xp']),
      ),
    ));
  }
}