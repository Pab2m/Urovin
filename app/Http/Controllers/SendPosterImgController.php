<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class SendPosterImgController //extends Controller
{
  var $ACCESS_TOKEN;
  var $idGryup;
  var $linkVkApiSendPosterImg;
  var $urlImg;
  var $upload_url;

  function __construct($urlImg) {//
    $this -> ACCESS_TOKEN = env('ACCESS_TOKEN');
    $this -> idGryup = env('idGryup');
    $templateVkApiSendPosterImg = "https://api.vk.com/method/photos.getOwnerCoverPhotoUploadServer?group_id=idGryup&crop_x=0&crop_y=0&crop_x2=1590&crop_y2=400&access_token=ACCESS_TOKEN&v=5.64";
    $this -> linkVkApiSendPosterImg = str_replace( "idGryup", $this -> idGryup, $templateVkApiSendPosterImg);
    $this -> linkVkApiSendPosterImg = str_replace( "ACCESS_TOKEN", $this -> ACCESS_TOKEN, $this -> linkVkApiSendPosterImg);
    $this -> urlImg = $urlImg;
    $this -> upload_url =  $this -> HttpRequestApiVkSend();

  }

  private function HttpRequestApiVkSend(){
      $HttpClient = new Client(['base_uri' => $this -> linkVkApiSendPosterImg]);
       $response = $HttpClient -> request('GET');
       $jsonResponse = $response->getBody()->getContents();
       $upload_url =  json_decode($jsonResponse)->response->upload_url;
       return $upload_url;
  }

  public function SendCoverToGroup(){
        $HttpClient = new Client(['base_uri' => $this -> upload_url]);
        $response = $HttpClient->request('POST', 'http://httpbin.org/post', [
        'multipart' => [
                ['name'    => 'photo',
                'contents' => fopen($this -> urlImg, 'r')]
        ]]);
        dd($HttpClient->getBody());
  }
}
