<?php

namespace App;
//&country=RU
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
    if($proxy instanceof Proxy && date($date) > date($proxy ->date)) {
       return false;
     }
     return $proxy;
    }

public static function CreateProxy($data){
  $Proxy = new Proxy();
  $Proxy -> fill($data);
  if($Proxy -> ReplayServer()){
   return $Proxy;
  } else {return false;}
 }

public function ReplayServer(){
       $healthy = array("!ip!", "!port!");
       $yummy   = array($this->ip, $this->port);
       $url = str_replace($healthy, $yummy, env('ReplayServer'));
       $ch = curl_init();
      // curl_setopt($ch, CURLOPT_URL, env('APP_URL').'/check');
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLINFO_HEADER_OUT, true);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_HEADER, 0);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
       $ch_errno = curl_errno($ch);
       $curl_error = curl_error($ch);
       $html = curl_exec($ch);
       curl_close($ch);
       if(!$ch_errno > 0){
        $ReaplyArray = json_decode($html, true);
        if((isset($ReaplyArray[0]['status'])) && ($ReaplyArray[0]['status'] =='ok')){
          return true;
        }
         return false;
       }
       return false;
    }

public function StrProxy(){
  return $this->ip.':'.$this->port;
}

}
