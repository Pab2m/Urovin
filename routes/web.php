<?php


Route::get('/', function () {
     return view('welcome');
});

Route::get('/meteorb', 'ParcerController@GetData');
Route::get('/test', 'UrovinController@parser');
