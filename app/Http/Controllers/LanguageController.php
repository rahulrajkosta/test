<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cookie;
use App;
use Session;

  class LanguageController extends Controller
  {
       


       public function setLanguage($lang) {

        $supportedLocales = ['en', 'es', 'de'];

        if (in_array($lang, $supportedLocales)) {
    
            \Cookie::queue(\Cookie::make('lang', $lang, '20160'));
            
            Session::put('language',$lang);

            \App::setLocale($lang);
    
        } else {
            \App::setLocale('en');
        }
    
        return back();

      }




  }