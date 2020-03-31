<?php
Route::get('/parser/{reka}', 'UrovinController@GetParser');//belaya
Route::get('/curl', 'UrovinController@curl');

Route::get('/test', 'UrovinController@getProxy');

Route::get('/group/download/cover/{reka}', 'UrovinController@DownloadGroupCover');
Route::match(['get','post'], '/api/vk', ['uses'=>'UrovinController@vk', 'as'=>'vk']);
Route::get('/img/{reka}', 'UrovinController@ImgPoster');

Route::get('/email', 'EmailController@EmailTest');

Route::get('/check', 'ProxyController@ProxyCheck');

//3ee402f7429bc7c3e6a83cdf61d18c19bac950e4eaee95f6b9f1f69756cfbcb53346d3180cd0a98848c23

//https://api.vk.com/method/photos.getOwnerCoverPhotoUploadServer?group_id=180881957&crop_x=0&crop_y=0&crop_x2=1590&crop_y2=400&access_token=3ee402f7429bc7c3e6a83cdf61d18c19bac950e4eaee95f6b9f1f69756cfbcb53346d3180cd0a98848c23&v=5.92
