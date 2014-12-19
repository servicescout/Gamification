<?php

namespace API\Event;

class LevelAnnounce extends \API\API
{
  private $config;

  public function __construct(\Auth\Auth $auth, $config = null)
  {
    parent::__construct($auth);

    $this->config = $config ?: \Util\Config::get();
  }

  protected function execute(&$response)
  {
    $sql = <<<SQL

WITH t_starting_stats AS (
  SELECT player_id, SUM(amount) AS xp, GET_LEVEL(SUM(amount)::INT) AS level
  FROM event.xp_accrual
  WHERE created_at < :start
  GROUP BY player_id
),
t_window_stats AS (
  SELECT player_id, SUM(amount) AS xp
  FROM event.xp_accrual
  WHERE created_at >= :start
  GROUP BY player_id
)
SELECT p.name, GET_LEVEL((COALESCE(t.xp, 0)::INT + w.xp)::INT) AS level
FROM t_window_stats w
INNER JOIN player p ON p.id = w.player_id
LEFT OUTER JOIN t_starting_stats t ON t.player_id = w.player_id
WHERE COALESCE(t.level, 1)::INT < GET_LEVEL((COALESCE(t.xp, 0)::INT + w.xp)::INT);

SQL;

    $connection = \Illuminate\Database\Eloquent\Model::getConnectionResolver()->connection();
    $increases = $connection->select($sql, array(
      // increases in the last 20 seconds
      'start' => date('Y-m-d H:i:s', time() - 20),
    ));

    $messages = array();

    foreach ($increases as $row)
    {
      $messages[] = $this->getMessageData($row['name'] . ' has advanced to level ' . $row['level'] . '!');
    }

    $response->addParam('messages', $messages);
  }

  private function getMessageData($message)
  {
    $path = $this->config->getValue('basePath');
    $filename = md5($message);

    if (!file_exists($path . '/web/upload/' . $filename . '.wav'))
    {
      exec('cd ' . $path . '/web/upload && echo "' . $message . '" | text2wave -scale 2 -o ' . $filename . '.wav -eval "(voice_cmu_us_clb_arctic_clunits)"');
    }

    return array(
      'text' => $message,
      'audioUrl' => $this->config->getValue('baseUrl') . '/upload/' . $filename . '.wav',
    );
  }
}