<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proxy;

class ProxyController extends Controller
{
  public static function  getProxy(){
    $Proxy = Proxy::AlreadyHaveADate(date('Y-m-d'));
    if($Proxy instanceof Proxy && $Proxy->ReplayServer()){
      return  $Proxy;
    }

    if(env('ProxyTypeSource') == 'ProxyList'){
     return ProxyController::ProxyList();
    }
    elseif(env('ProxyTypeSource') == 'ProxyOne') {
     return ProxyController::ProxyOne();
    }
  EmailController::EmailSubnet('Попытка получения Proxy не удалась!!!');
  exit;
}

private static function ProxyList(){
  $ch = curl_init(env('ProxyList'));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $html = curl_exec($ch);
  $curl_errno = curl_errno($ch);
  $curl_error = curl_error($ch);
  curl_close($ch);
  if(!$curl_errno > 0){
    $lines = preg_split('/\\r\\n?|\\n/', $html);
    foreach ($lines as $key => $value) {
    $lineProxy = preg_split('/:/', $value);
    $arrayProxy = array('ip' => $lineProxy[0], 'port'=>$lineProxy[1], 'date'=>date('Y-m-d'));
    $Proxy = Proxy::CreateProxy($arrayProxy);
    if($Proxy instanceof Proxy){
       $Proxy -> save();
       return $Proxy;
      }
    }
  } else{
    EmailController::EmailSubnet('Ошибка при получение Proxy!!! На сервере ProxyList!');
    exit;
    }
}

 private static function ProxyOne() {
   $i = 0;
   $Proxy = false;
    while ($i < 8) {
     if($Proxy instanceof Proxy && $Proxy->ReplayServer()){
       return  $Proxy;
     }
   if($i > 3){
      $ProxyList =  env('ProxyOne');
   } else {$ProxyList =  env('ProxyOne').'&country=RU';}

   $ch = curl_init($ProxyList);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   $data = curl_exec($ch);
   $json_data = json_decode($data);
   curl_close($ch);
   if(property_exists($json_data, "error")){
      EmailController::EmailSubnet('Ошибка при получение Proxy!!! На сервере ProxyOne!');
      exit;
   }
   $proxy_array = array('ip' => $json_data->ip, 'port' => $json_data-> port, 'date'=> date('Y-m-d'));
   $Proxy = Proxy::CreateProxy($proxy_array);

   if($Proxy instanceof Proxy) {
     $Proxy->save();
     return $Proxy;
    }
    echo $i;
    $i++;
   }
   EmailController::EmailSubnet($i.' Попытка получения Proxy не удалась!!!');
   exit;
}

}
