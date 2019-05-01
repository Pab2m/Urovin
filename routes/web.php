<?php



// Route::get('/', function () {
//      return view('welcome');
// });
// Route::get('/', function()
// {
// $manager = new Intervention\Image\ImageManager(array('driver' => 'imagick'));
// $image = Image::make($_SERVER['DOCUMENT_ROOT'].'/foo.jpg');
// $image->text("8814", 20, 180, function($font){
//   $font->size(50);
//   $font->color('#000');
// });
// $image->save($_SERVER['DOCUMENT_ROOT'].'/foo88.jpg');
//
//
// });

Route::get('/parser', 'UrovinController@GetParser');
// Route::get('/test', 'UrovinController@generationImg');
Route::get('/test', 'UrovinController@Test');
Route::match(['get','post'], '/api/vk', ['uses'=>'UrovinController@vk', 'as'=>'vk']);



//3ee402f7429bc7c3e6a83cdf61d18c19bac950e4eaee95f6b9f1f69756cfbcb53346d3180cd0a98848c23

//https://api.vk.com/method/photos.getOwnerCoverPhotoUploadServer?group_id=180881957&crop_x=0&crop_y=0&crop_x2=1590&crop_y2=400&access_token=3ee402f7429bc7c3e6a83cdf61d18c19bac950e4eaee95f6b9f1f69756cfbcb53346d3180cd0a98848c23&v=5.92
