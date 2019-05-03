<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Image;
use Intervention\Image\ImageManager;

class Urovin extends Model{
    protected $table =  'urovin';
    protected $fillable = ['date', 'reka_id', 'reka_name', 'urovin', 'delta'];

public static function UrovinNaDatu($date){
  $urovin = Urovin::where('date', $date)->get();
  if($urovin->count() >= 1){
    return false;
  }
  return true;
}

public static function generationImg(){

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




}
