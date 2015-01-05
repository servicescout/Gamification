<?php

namespace Game\Level;

class Helper
{
  public function getLevelForXP($xp)
  {
    return floor(pow($xp, 0.8) / 10) + 1;
  }

  public function getPercentToNextLevel($xp)
  {
    return floor(((pow($xp, 0.8) / 10) + 1) * 100) % 100;
  }

  public function getXPNeededForNextLevel($xp)
  {
    return ceil(floor(pow($this->getLevelForXP($xp) * 10, 10 / 8))) - ($xp - 1);
  }
}