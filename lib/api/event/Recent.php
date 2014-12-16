<?php

namespace API\Event;

class Recent extends \API\API
{
  public function execute()
  {
    $con = \Illuminate\Database\Eloquent\Model::getConnectionResolver()->connection();

    $this->addParam('recent', $con->select(<<<SQL

(
  SELECT
    'Gold' AS type,
    p.name AS to_name,
    COALESCE(pp.name, 'Auto') AS from_name,
    amount,
    description,
    g.created_at
  FROM event.gold_transfer g
  INNER JOIN public.player p ON p.id = to_player_id
  INNER JOIN public.player pp ON pp.id = from_player_id

  UNION

  SELECT
    'XP' AS type,
    p.name AS to_name,
    'Auto' AS from_name,
    amount,
    description,
    xp.created_at
  FROM event.xp_accrual xp
  INNER JOIN public.player p ON p.id = xp.player_id
  WHERE xp.amount != 0
)

ORDER BY created_at DESC
LIMIT 10;

SQL
    ));
  }
}