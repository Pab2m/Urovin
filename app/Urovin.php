<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Urovin extends Model{
    protected $table =  'urovin';
    protected $fillable = ['date', 'reka_id', 'reka_name', 'urovin', 'delta'];

public static function UrovinNaDatu($date){
  $urovin = Urovin::where('date', $date)->get();
  if($urovin->count() >= 1){
    return false;
  }
  return true;
}
public static function UrovinAdd($data){

}

}
