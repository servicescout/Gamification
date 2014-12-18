<?php

namespace API\Guild;

class Get extends \API\API
{
  private $guildId;

  public function __construct($auth, $guildId)
  {
    parent::__construct($auth);

    $this->guildId = $guildId;
  }

  public function execute(&$response)
  {
    $guild = \Model\Entity\Guild::find($this->guildId)->toArray();

    $response->addParam('guild', $guild);
  }
}