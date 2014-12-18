<?php

namespace Model\Entity;

use Illuminate\Database\Eloquent\Model;

class Guild extends Model
{
  protected $table = 'public.guild';
  public function getDates() { return array(); }
}