<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
     protected $table =  'proxy';
    protected $fillable = ['ip', 'port', 'date'];

  public static function last()
  {
      $order = Proxy::latest()->get()->first();
      return $order;
  }

  public static function AlreadyHaveADate($date){
  $proxy = Proxy::last();
    if(date($date) > date($proxy ->date)) {
       return false;
     }
     return $proxy;
    }
}
