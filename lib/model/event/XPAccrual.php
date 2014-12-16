<?php

namespace Model\Event;

use Illuminate\Database\Eloquent\Model;

class XPAccrual extends Model
{
  protected $table = 'event.xp_accrual';
  public function getDates() { return array(); }
}