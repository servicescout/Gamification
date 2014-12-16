<?php

namespace Game\Level;

class Helper
{
  public function getLevelForXP($xp)
  {
    return floor(pow($xp + 1, 0.4));
  }
  
  public function getPercentToNextLevel($xp)
  {
    return floor((pow($xp + 1, 0.4)) * 100) % 100;
  }
  
  public function getXPNeededForNextLevel($xp)
  {
    return ceil(pow($this->getLevelForXP($xp) + 1, 10 / 4)) - ($xp + 1);
  }
}