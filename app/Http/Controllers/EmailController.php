<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailController extends Controller
{
public static function EmailSubnet($text)
{
  \Mail::raw($text, function($mail) {
    $mail->subject('Уровень');
    $mail->from(env('EmailServera'), env('EmailServera'));
    $mail->to(env('EmailRepost'));//$toEmail
});
}

public function EmailTest(){
  //$this -> EmailSubnet('title', 'post');
}

}
