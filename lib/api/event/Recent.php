<?php

namespace API\Event;

class Recent extends \API\API
{
  private $requestData;

  public function __construct(\Auth\Auth $auth, $requestArray)
  {
    parent::__construct($auth);

    $requestData = new \Util\Config();

    foreach ($requestArray as $name => $value)
    {
      $requestData->setValue($name, $value);
    }

    $this->requestData = $requestData;
  }

  public function execute(&$response)
  {
    $con = \Illuminate\Database\Eloquent\Model::getConnectionResolver()->connection();

    $playerId = $this->requestData->getValue('playerId');

    $sql = <<<SQL

(
  SELECT
    'Gold' AS type,
    COALESCE(p.name, 'The Bank') AS to_name,
    COALESCE(pp.name, 'The Bank') AS from_name,
    amount,
    description,
    g.created_at
  FROM event.gold_transfer g
  LEFT OUTER JOIN public.player p ON p.id = to_player_id
  LEFT OUTER JOIN public.player pp ON pp.id = from_player_id

SQL;

    if (!is_null($playerId))
    {
      $sql .= 'WHERE p.id = :playerId';
    }

    $sql .= <<<SQL

  UNION

  SELECT
    'XP' AS type,
    p.name AS to_name,
    'NA' AS from_name,
    amount,
    description,
    xp.created_at
  FROM event.xp_accrual xp
  INNER JOIN public.player p ON p.id = xp.player_id
  WHERE xp.amount != 0

SQL;

    if (!is_null($playerId))
    {
      $sql .= 'AND p.id = :playerId';
    }

    $sql .= <<<SQL

)

ORDER BY created_at DESC
LIMIT 10;

SQL;

    $params = array();

    if (!is_null($playerId))
    {
      $params = array(
        'playerId' => $playerId,
      );
    }

    $response->addParam('recent', $con->select($sql, $params));
  }
}