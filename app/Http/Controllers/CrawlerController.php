<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerController extends Controller
{
   public static function CrawlerProxyList($html){
      $crawler = new Crawler($html);
      $spanClassSf_dump_str = $crawler->filter("p")->children();;
      dd($spanClassSf_dump_str);
   }
}
