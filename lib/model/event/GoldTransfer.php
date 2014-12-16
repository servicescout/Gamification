<?php

namespace Model\Event;

use Illuminate\Database\Eloquent\Model;

class GoldTransfer extends Model
{
  protected $table = 'event.gold_transfer';
  public function getDates() { return array(); }
}