<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Urovin;
use App\UrovinOnYesterdayToday;
use Intervention\Image\ImageManager;
use Image;
use App\Reka;
use App\Proxy;

class UrovinController extends Controller
{
    private function Parser(){
     $Proxy = ProxyController::getProxy();
      if(!$Proxy instanceof Proxy){
        EmailController::EmailSubnet('Ошибка при получение Propxy в Parser()!!!');
        exit;
      }
     $ch = curl_init();
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
      curl_setopt($ch, CURLOPT_URL, env('URL_PARSER', '1'));
      curl_setopt($ch, CURLOPT_PROXY, $Proxy->StrProxy());
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

      $html = curl_exec($ch);

      $curl_errno = curl_errno($ch);
      $curl_error = curl_error($ch);
      curl_close($ch);

      if(!$curl_errno > 0) {
      // Create new instance for parser.
      $crawler = new Crawler(null, env('URL_PARSER', '1'));
      $crawler->addHtmlContent($html, 'UTF-8');
      // Get title text.
      $urovin_tr_selected_rives = $crawler->filter("table tr")->eq(1) ->children();//->eq(4)->text() ;
      $Data = [];
      $Data['reka_name'] = strip_tags($urovin_tr_selected_rives -> eq(0) -> text());
      $Data['site'] = strip_tags($urovin_tr_selected_rives -> eq(1) -> text());
      $Data['poiima'] = strip_tags($urovin_tr_selected_rives -> eq(2) -> text());
      $Data['urovin'] = strip_tags($urovin_tr_selected_rives -> eq(3) -> text());
      $Data['delta'] = strip_tags($urovin_tr_selected_rives -> eq(4) -> text());
      $Str_data = $crawler->filter("div.entry-content p")->text();

      $Array_date = array();

      preg_match("/.*([0-9]{2}\\.[0-9]{2}\\.[0-9]{4}).*/",$Str_data, $Array_date);
      $d1 = strtotime($Array_date[1]);
      $Data['date'] =  date("Y-m-d", $d1);
      return $Data;
    } else {
      EmailController::EmailSubnet('Ошибка при получение узлов!!!');
      exit;
     }
  }
   public function GetParser($reka) {
      $date = date('Y-m-d');
      $Reka = Reka::where('url', $reka)->first();
      if(Urovin::UrovinNaDatu($date, $Reka)) {
        $River_level = $this->Parser();
        $River_level['reka_id'] = $Reka->id;
       if(!Urovin::AlreadyHaveADate($River_level['date'])){
          $urovin = Urovin::create($River_level);
         }
      }
      if (isset($urovin) && $urovin instanceof Urovin){
        EmailController::EmailSubnet($urovin -> urovin.' см!');
        return dd(1);
      }
      EmailController::EmailSubnet('Ошибка  в GetParser на '.$date);
      return dd(0);
    }

    public static function generationImg($UrovinOnYesterdayToday, $name_img, $name_reki, $relative = false){ //class UrovinOnYesterdayToday
      if(!$UrovinOnYesterdayToday instanceof UrovinOnYesterdayToday && $UrovinOnYesterdayToday->count = 0){
        if(!$relative){
          return $_SERVER['DOCUMENT_ROOT'].'/foo.jpg';
        }
          return '/foo.jpg';
        }

      $manager = new ImageManager(array('driver' => 'imagick'));
      $image = $manager->canvas(150, 70);
      $image -> text("р.".$name_reki, 4, 20, function($font){
      $font -> size(24);
      $font -> file($_SERVER['DOCUMENT_ROOT'].'/ttf/AmaticSC-Bold.ttf');
    });
   //"283 см"
      $image -> text($UrovinOnYesterdayToday -> yesterdayUrovin.' см', 5, 44, function($font){
      $font -> size(24);
      $font -> file($_SERVER['DOCUMENT_ROOT'].'/ttf/AmaticSC-Bold.ttf');
    });
    $image -> text($UrovinOnYesterdayToday -> yesterdayDate, 4, 63, function($font){
      $font -> size(14);
      $font -> file($_SERVER['DOCUMENT_ROOT'].'/ttf/Oswald-Regular.ttf');
    });

    //  $image->insert($_SERVER['DOCUMENT_ROOT'].'/img/volna.png',null,61,5);

      $image->text($UrovinOnYesterdayToday -> delta." см",80,21, function($font){
        $font -> size(24);
        $font -> file($_SERVER['DOCUMENT_ROOT'].'/ttf/AmaticSC-Bold.ttf');
      });

      $image->text($UrovinOnYesterdayToday -> todayUrovin." см",87,45, function($font){
        $font -> size(24);
        $font -> file($_SERVER['DOCUMENT_ROOT'].'/ttf/AmaticSC-Bold.ttf');
      });

      $image -> text($UrovinOnYesterdayToday -> todayDate, 82, 63, function($font){
        $font -> size(14);
        $font -> file($_SERVER['DOCUMENT_ROOT'].'/ttf/Oswald-Regular.ttf');
      });

     $poster = Image::make($_SERVER['DOCUMENT_ROOT'].'/foo.jpg');
     $image-> save($_SERVER['DOCUMENT_ROOT'].'/'.$name_img.'_urovin.png');
     $poster -> insert($_SERVER['DOCUMENT_ROOT'].'/'.$name_img.'_urovin.png', null, 120, 130 );
     $poster-> save($_SERVER['DOCUMENT_ROOT'].'/'.$name_img.'.jpg');
    if(!$relative){
      return $_SERVER['DOCUMENT_ROOT'].'/'.$name_img.'.jpg';}
      return '/'.$name_img.'.jpg';
    }


  public function DownloadGroupCover($reka){
     $Reka = Reka::where('url', $reka)->first();
     if($Reka instanceof Reka){
       $UrovinOnYesterdayToday = new UrovinOnYesterdayToday(date("m.d.y"), $Reka->id);
       $PosterImg = new SendPosterImgController($this -> generationImg($UrovinOnYesterdayToday));
       $PosterImg -> SendCoverToGroup();
      }
    }

  public function GetCurrentWater(){
    $UrovinOnYesterdayToday = new UrovinOnYesterdayToday(date("d.m.Y"));
  }


public function ImgPoster($url)
{
   $Reka = Reka::where('url', $url)->first();
   if($Reka instanceof Reka){
      $UrovinOnYesterdayToday = new UrovinOnYesterdayToday(time(), $Reka->id);//date("d.m.y")
      return '<img src="'.$this -> generationImg($UrovinOnYesterdayToday, $url, $Reka->name ,true).'">';
   }
}


}
