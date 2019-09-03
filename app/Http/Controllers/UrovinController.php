<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Urovin;
use App\UrovinOnYesterdayToday;
use Intervention\Image\ImageManager;
use Image;
use App\Reka;

class UrovinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    private function Parser(){
      $html = file_get_contents(env('URL_PARSER', '1'));
      // Create new instance for parser.
      $crawler = new Crawler(null, env('URL_PARSER', '1'));
      $crawler->addHtmlContent($html, 'UTF-8');
      // Get title text.
      $urovin_tr_belaj_sterlitamak = $crawler->filter("table tr")->eq(1) ->children();//->eq(4)->text() ;
      $Data = [];
      $Data['reka_name'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(0) -> text());
      $Data['site'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(1) -> text());
      $Data['poiima'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(2) -> text());
      $Data['urovin'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(3) -> text());
      $Data['delta'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(4) -> text());
      $Str_data = $crawler->filter(".entry-content p")->text();
      $Array_date = array();

      preg_match("/.*([0-9]{2}\\.[0-9]{2}\\.[0-9]{4}).*/",$Str_data, $Array_date);
      $d1 = strtotime($Array_date[1]);
      $Data['date'] =  date("Y-m-d", $d1);;

      return $Data;
    }

   public function GetParser($reka) {
      $date = date('Y-m-d');
      $Reka = Reka::where('url', $reka)->first();
      if(Urovin::UrovinNaDatu($date, $Reka)) {
        $Belaj_sterlitamak = $this->Parser();
        $Belaj_sterlitamak['reka_id'] = $Reka->id;
        $urovin = Urovin::create($Belaj_sterlitamak);
      }
    }

    public static function generationImg($UrovinOnYesterdayToday){ //class UrovinOnYesterdayToday
      if($UrovinOnYesterdayToday->count < 0){
        return;
      }

      $manager = new ImageManager(array('driver' => 'imagick'));
      $image = $manager->canvas(150, 70);
      $image -> text("р.Белая", 4, 20, function($font){
      $font -> size(24);
      $font -> file($_SERVER['DOCUMENT_ROOT'].'/ttf/AmaticSC-Bold.ttf');
    });

    $image -> text("283 см", 5, 44, function($font){
      $font -> size(24);
      $font -> file($_SERVER['DOCUMENT_ROOT'].'/ttf/AmaticSC-Bold.ttf');
    });
    $image -> text("07.04.2019", 4, 63, function($font){
      $font -> size(14);
      $font -> file($_SERVER['DOCUMENT_ROOT'].'/ttf/Oswald-Regular.ttf');
    });

    //  $image->insert($_SERVER['DOCUMENT_ROOT'].'/img/volna.png',null,61,5);

      $image->text("+30 см",80,21, function($font){
        $font -> size(24);
        $font -> file($_SERVER['DOCUMENT_ROOT'].'/ttf/AmaticSC-Bold.ttf');
      });

      $image->text("313 см",87,45, function($font){
        $font -> size(24);
        $font -> file($_SERVER['DOCUMENT_ROOT'].'/ttf/AmaticSC-Bold.ttf');
      });

      $image -> text("08.04.2019", 82, 63, function($font){
        $font -> size(14);
        $font -> file($_SERVER['DOCUMENT_ROOT'].'/ttf/Oswald-Regular.ttf');
      });

     $poster = Image::make($_SERVER['DOCUMENT_ROOT'].'/foo.jpg');
    $image-> save($_SERVER['DOCUMENT_ROOT'].'/urovin.png');
     $poster -> insert($_SERVER['DOCUMENT_ROOT'].'/urovin.png', null, 120, 130 );
     $poster-> save($_SERVER['DOCUMENT_ROOT'].'/foo88.jpg');
     return $_SERVER['DOCUMENT_ROOT'].'/foo88.jpg';
    }


  public function DownloadGroupCover(){
     $PosterImg = new SendPosterImgController($this -> generationImg());
     $PosterImg -> SendCoverToGroup();
    }

  public function GetCurrentWater(){
    $UrovinOnYesterdayToday = new UrovinOnYesterdayToday(date("d.m.Y"));
  //  dd($UrovinOnYesterdayToday -> count);
  }


}
