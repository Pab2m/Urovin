<?php

namespace App;

class UrovinOnYesterdayToday
{
var $date, $todayDate,$yesterdayDate,$todayUrovin, $yesterdayUrovin;

function __construct($TodayData, $reka_id = 1){//$TodayData = date("d.m.Y"), $reka_id = 1)
    $this -> date = $TodayData;
    $this -> CollectionUrovin = Urovin::where('reka_id', $reka_id)->where('date', '<=', $TodayData)->orderBy('date', 'desc')->limit(2)->get();
    $this -> count = $this -> CollectionUrovin -> count();
    $this -> todayUrovin = $this -> today()->urovin;
    $this -> yesterdayUrovin = $this -> yesterday()->urovin;
    $this -> todayDate =  date('d.m.Y', strtotime($this -> today()->date));
    $this -> yesterdayDate = date('d.m.Y', strtotime($this -> yesterday()->date));
    $this -> delta = $this -> CollectionUrovin[0]-> delta;
}

public function today() {//return date('d.m.Y', strtotime($this -> CollectionUrovin[0]));
  return $this -> CollectionUrovin[0];
}
public function  yesterday(){
  return $this -> CollectionUrovin[1];
}


}
