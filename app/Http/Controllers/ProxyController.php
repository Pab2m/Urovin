<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proxy;

class ProxyController extends Controller
{
  public static function  getProxy(){

    $proxy = Proxy::AlreadyHaveADate(date('Y-m-d'));

    if($proxy instanceof Proxy){
      return  $proxy;
    }
    $headers = array(
                      'cache-control: max-age=0',
                      'upgrade-insecure-requests: 1',
                      'user-agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:73.0) Gecko/20100101 Firefox/73.0',
                      'sec-fetch-user: ?1',
                      'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
                      'x-compress: null',
                      'sec-fetch-site: none',
                      'sec-fetch-mode: navigate',
                      'accept-encoding: deflate, br',
                      'accept-language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7');

  $ch = curl_init(env('ProxyList'));

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  $data = curl_exec($ch);
  $json_data = json_decode($data);
  curl_close($ch);
  if(property_exists($json_data, "error")){
     EmailController::EmailSubnet('Ошибка при получение Proxy!!!');
     exit;
  }
  $proxy_array = array('ip' => $json_data->ip, 'port' => $json_data-> port, 'date'=> date('Y-m-d'));
  return Proxy::create($proxy_array);
  }
}
