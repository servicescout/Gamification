<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
  protected $table = 'public.account';
  public function getDates() { return array(); }
}