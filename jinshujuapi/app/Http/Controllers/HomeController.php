<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Socialite;
use App\Http\Requests;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }
    
  
    /**
     * Show the application call.
     *
     * @return \Illuminate\Http\Response
     */
    public function auth()
    {
       return \Socialite::with('jinshuju')->redirect();
        // return \Socialite::with('weibo')->scopes(array('email'))->redirect();
    }


    public function callback() {
        $oauthUser = \Socialite::with('jinshuju')->user();

        var_dump($oauthUser->getNickname());
        var_dump($oauthUser->getEmail());
        var_dump($oauthUser->getAvatar());
        var_dump($oauthUser->getToken());
        var_dump($oauthUser->getRefreshToken());
        var_dump($oauthUser->getExpiresIn());

        $oauthUser2 = \Socialite::with('jinshuju')->refresh($oauthUser->getRefreshToken());

        var_dump($oauthUser2->getNickname());
        var_dump($oauthUser2->getEmail());
        var_dump($oauthUser2->getAvatar());
        var_dump($oauthUser2->getToken());
        var_dump($oauthUser2->getRefreshToken());
        var_dump($oauthUser2->getExpiresIn());
        
        //return redirect("/home"."?token=".$oauthUser->getToken());
    }

    public function index() {
        
        $oauthUser = \Socialite::with('jinshuju')->user();

    }

}
