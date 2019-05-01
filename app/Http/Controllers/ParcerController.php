<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class ParcerController extends Controller{

public static function GetData(){

  $html = file_get_contents(env('URL_PARSER', '1'));

 // Create new instance for parser.
 $crawler = new Crawler(null, env('URL_PARSER', '1'));
 $crawler->addHtmlContent($html, 'UTF-8');
 // Get title text.
 $urovin_tr_belaj_sterlitamak = $crawler->filter("table tr")->eq(1) ->children();//->eq(4)->text() ;
 $Belaj_sterlitamak = [];
 $Belaj_sterlitamak['reka'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(0) -> text());
 $Belaj_sterlitamak['site'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(1) -> text());
 $Belaj_sterlitamak['poiima'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(2) -> text());
 $Belaj_sterlitamak['urovin'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(3) -> text());
 $Belaj_sterlitamak['delta'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(4) -> text());

 $Str_data = $crawler->filter(".entry-content p")->text();
 $Array_date = array();

 preg_match("/.*([0-9]{2}\\.[0-9]{2}\\.[0-9]{4}).*/",$Str_data, $Array_date);

 $Belaj_sterlitamak['data'] = $Array_date[1];
 return $Belaj_sterlitamak;
}


}
