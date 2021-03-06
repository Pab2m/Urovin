<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Urovin extends Model{
    protected $table =  'urovin';
    protected $fillable = ['date', 'reka_id', 'reka_name', 'urovin', 'delta'];

public static function UrovinNaDatu($date, $Reka){
  if(!$Reka instanceof Reka){
    return false;
  }
  $urovin = Urovin::where('date', $date)->where('reka_id', $Reka->id)->get();
  if($urovin->count() >= 1){
    return false;
  }
  return true;
}

public static function last()
{
    $order = Urovin::latest()->get()->first();
    return $order;
}

public static function AlreadyHaveADate($date){
   $urovin = Urovin::last();
  if($urovin instanceof Urovin && date($date) > date($urovin->date)) {
    return false;
   }
    exit;
  }
}
