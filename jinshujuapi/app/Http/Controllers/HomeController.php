<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
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
       return Socialite::with('jinshuju')->redirect();
        // return \Socialite::with('weibo')->scopes(array('email'))->redirect();
    }


    public function callback(Request $request) {
        Socialite::with('jinshuju')->user();
  
        return redirect("/home");
    }

    public function index(Request $request) {

        Socialite::with('jinshuju')->refresh();
        
        $email= session('email');
        $user = DB::table('users')->where('email',$email)
                                  ->get();

        $token = $user[0]->access_token;

        $forms = Socialite::with('jinshuju')->getFormByToken($token);

        return view('welcome',['me' => $email,'forms' => $forms,'token' => $token]);

    }

    public function field(Request $request)
    {
      Socialite::with('jinshuju')->refresh();
      $form = $request->select_form;
      
      $email= session('email');
      $user = DB::table('users')->where('email',$email)
                                ->get();

      $token = $user[0]->access_token;
      if(isset($form)){ 
        $fields = Socialite::with('jinshuju')->getFeildByToken($form,$token);

        return $fields;
      }
    }

}
