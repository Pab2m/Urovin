<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Urovin;

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
      $link = 'http://www.meteorb.ru/urovni-rek';
      $html = file_get_contents($link);
      // Create new instance for parser.
      $crawler = new Crawler(null, $link);
      $crawler->addHtmlContent($html, 'UTF-8');
      // Get title text.
      $urovin_tr_belaj_sterlitamak = $crawler->filter("table tr")->eq(1) ->children();//->eq(4)->text() ;
      $Belaj_sterlitamak = [];
      $Belaj_sterlitamak['reka_name'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(0) -> text());
      $Belaj_sterlitamak['site'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(1) -> text());
      $Belaj_sterlitamak['poiima'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(2) -> text());
      $Belaj_sterlitamak['urovin'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(3) -> text());
      $Belaj_sterlitamak['delta'] = strip_tags($urovin_tr_belaj_sterlitamak -> eq(4) -> text());

      $Str_data = $crawler->filter(".entry-content p")->text();
      $Array_date = array();

      preg_match("/.*([0-9]{2}\\.[0-9]{2}\\.[0-9]{4}).*/",$Str_data, $Array_date);
      $d1 = strtotime($Array_date[1]);
      $Belaj_sterlitamak['date'] =  date("Y-m-d", $d1);;

      return $Belaj_sterlitamak;
    }

   public function Test() {
      $date = date('Y-m-d');
      if(Urovin::UrovinNaDatu($date)) {
        $Belaj_sterlitamak = $this->Parser();
        $Belaj_sterlitamak['reka_id'] = 1;
        $urovin = Urovin::create($Belaj_sterlitamak);
        dd($urovin);
      }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
