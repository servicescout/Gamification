<?php

namespace API\Event;

class Missed extends \API\API
{
  public function execute()
  {
    $con = \Illuminate\Database\Eloquent\Model::getConnectionResolver()->connection();

    $this->addParam('missed', $con->select(<<<SQL

SELECT
  'XP' AS type,
  p.name AS to_name,
  'Auto' AS from_name,
  amount,
  description,
  xp.created_at
FROM event.xp_accrual xp
INNER JOIN public.player p ON p.id = xp.player_id

WHERE xp.amount = 0

ORDER BY xp.created_at DESC
LIMIT 10;

SQL
    ));
  }
}