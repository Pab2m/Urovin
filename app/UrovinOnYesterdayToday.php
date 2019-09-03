<?php

namespace App;

class UrovinOnYesterdayToday
{
var $Yesterday, $date;

function __construct($TodayData, $reka_id = 1){//$TodayData = date("d.m.Y"), $reka_id = 1)

    $this -> date = $TodayData;
    $this -> CollectionUrovin = Urovin::where('reka_id', $reka_id)->where('date', '<=', $TodayData)->orderBy('date', 'desc')->limit(2)->get();
    $this -> count = $this -> CollectionUrovin -> count();
}

public function today() {
  return $this -> CollectionUrovin[0];
}
public function  yesterday(){
  return $this -> CollectionUrovin[1];
}


}
