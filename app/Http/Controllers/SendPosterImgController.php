<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class SendPosterImgController //extends Controller
{
  var $ACCESS_TOKEN;
  var $idGryup;
  var $linkVkApiSendPosterImg, $linkVkApiPosterImgseven;
  var $urlImg;
  var $upload_url;
  var $HASH, $POTHO;
  var $templateVkApiSendPosterImg, $templateVkApiPosterImgSeve;

  function __construct($urlImg) {//
    $this -> ACCESS_TOKEN = env('ACCESS_TOKEN');
    $this -> idGryup = env('idGryup');
    $this -> templateVkApiSendPosterImg = 'https://api.vk.com/method/photos.getOwnerCoverPhotoUploadServer?group_id=idGryup&crop_x=0&crop_y=0&crop_x2=1590&crop_y2=400&access_token=ACCESS_TOKEN&v=5.64';
	$this -> templateVkApiPosterImgSeve = 'https://api.vk.com/method/photos.saveOwnerCoverPhoto?hash=HASH&photo=PHOTO&access_token=ACCESS_TOKEN&v=5.64';
    $this -> linkVkApiSendPosterImg = str_replace( "idGryup", $this -> idGryup, $this->templateVkApiSendPosterImg);
    $this -> linkVkApiSendPosterImg = str_replace( "ACCESS_TOKEN", $this -> ACCESS_TOKEN, $this -> linkVkApiSendPosterImg);
    $this -> linkVkApiPosterImgSave = '';
    $this -> urlImg = $urlImg;
    $this -> upload_url =  $this -> HttpRequestApiVkSend();
	$this -> HASH = '';
	$this -> PHOTO = '';
  }

  private function CraleLinkVkApiPosterImgSave(){
	  $this -> linkVkApiPosterImgSave = str_replace( "HASH", $this -> HASH, $this -> templateVkApiPosterImgSeve);
	  $this -> linkVkApiPosterImgSave = str_replace( "ACCESS_TOKEN", $this -> ACCESS_TOKEN, $this -> linkVkApiPosterImgSave);
	  $this -> linkVkApiPosterImgSave = str_replace( "PHOTO", $this -> PHOTO, $this -> linkVkApiPosterImgSave);
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
        $response = $HttpClient->request('POST', $this -> upload_url, [
        'multipart' => [
                ['name'    => 'photo',
                'contents' => fopen($this -> urlImg, 'r')]
        ]]);
		$Response = json_decode($response->getBody()->getContents());
        $this -> HASH = $Response ->hash;
		$this -> PHOTO = $Response ->photo;
		$this -> CraleLinkVkApiPosterImgSave();
		$HttpClient = new Client(['base_uri' => $this -> linkVkApiPosterImgSave]);
        $response = $HttpClient -> request('GET');
		dd($this -> linkVkApiPosterImgSave);
  }
}
