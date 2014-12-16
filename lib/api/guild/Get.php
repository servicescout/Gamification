<?php

namespace API\Guild;

class Get extends \API\API
{
  private $guildId;

  public function __construct($guildId)
  {
    $this->guildId = $guildId;
  }

  public function execute()
  {
    $guild = \Model\Guild::find($this->guildId)->toArray();

    $this->addParam('guild', $guild);
  }
}