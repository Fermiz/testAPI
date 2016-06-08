<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;

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
    public function call()
    {
        $url = "https://account.jinshuju.net/oauth/token";
        $params = array(
            "client_id" => "b4bf49696999fae5056acacf659b449f73fe9639c03213a673d763e0c5f04088",
            "client_secret" => "7d785526a9fa3b1c3c2cd7c77fc9746ad6950ec629da0c565e005186e0b8a8c0",
            "code" => $_GET['code'],
            "redirect_uri" => "http://localhost/home/callback",
            'grant_type' => 'authorization_code'//isset($options['grant_type']) ? $options['grant_type'] : 'authorization_code',
            //'header'    => isset($options['header']) ? $options['header'] : '',
            );
       
        $postdata = http_build_query($params);
        $opts = array(
                    'http' => array(
                        'method'  => 'POST',
                        'header'  => 'Content-type: application/x-www-form-urlencoded',//. PHP_EOL . $params['header'],
                        'content' => $postdata
                    )
                );
        $_default_opts = stream_context_get_params(stream_context_get_default());
        $context = stream_context_create(array_merge_recursive($_default_opts['options'], $opts));
        $response = file_get_contents($url, false, $context);
        
        $return = json_decode($response, true);

        date_default_timezone_set('Asia/Shanghai');
        $current_time = Carbon::now();

        DB::table('tokens')->insert(
        [ 'access_token' => $return['access_token'], 
          'token_type' => $return['token_type'],
          'expires_in' => $return['expires_in'],
          'refresh_token' => $return['refresh_token'],
          'scope' => $return['scope'],
          'created_at' => $current_time,
          'updated_at' => $current_time 
        ]
        );

        $token = DB::table('tokens')->where('access_token', $return['access_token'])->first();

        //print_r($token->access_token);
        return redirect("/home"."?tokenid=".$token->id);

    }
    

   public function index(Request $request)
    {
       //refresh_token
       $tokenid= $request->tokenid;

       $token = DB::table('tokens')->where('id', $tokenid)->first();
       
       date_default_timezone_set('Asia/Shanghai');
       $current_time = Carbon::now();

       $expires_time = strtotime($token->updated_at) + $token->expires_in;
       //令牌超时刷新
       if (strtotime($current_time) >= $expires_time){
            $tokenurl = "https://account.jinshuju.net/oauth/token";
            $params = array(
                "client_id" => "b4bf49696999fae5056acacf659b449f73fe9639c03213a673d763e0c5f04088",
                "client_secret" => "7d785526a9fa3b1c3c2cd7c77fc9746ad6950ec629da0c565e005186e0b8a8c0",
                "refresh_token" => $token->refresh_token,
                'grant_type' => isset($options['grant_type']) ? $options['grant_type'] : 'refresh_token',
                'header'    => isset($options['header']) ? $options['header'] : '',
                );
           
            $postdata = http_build_query($params);
            $opts = array(
                        'http' => array(
                            'method'  => 'POST',
                            'header'  => 'Content-type: application/x-www-form-urlencoded'. PHP_EOL . $params['header'],
                            'content' => $postdata
                        )
                    );
            $_default_opts = stream_context_get_params(stream_context_get_default());
            $context = stream_context_create(array_merge_recursive($_default_opts['options'], $opts));
            $response = file_get_contents($tokenurl, false, $context);
            
            $return = json_decode($response, true);

            DB::table('tokens')
                    ->where('id', $token->id)
                    ->update(['access_token' => $return['access_token'], 
                              'refresh_token' => $return['refresh_token'],
                              'updated_at' => $current_time
                            ]);

            return redirect("/home"."?tokenid=".$token->id);
       }
       
       $infourl = 'https://api.jinshuju.net/v4/me?access_token='.$token->access_token;
       $me = json_decode(file_get_contents($infourl),true);

       $formurl = 'https://api.jinshuju.net/v4/forms?access_token='.$token->access_token;
       $forms = json_decode(file_get_contents($formurl),true);

       //var_dump($me);
       //var_dump($forms);
       return view('welcome',['me' => $me['email'],'forms' => $forms,'access_token' => $token->access_token]);
    }


    public function field(Request $request)
    {
      $form = $request->select_form;
      $access_token = $request->access_token;
        if(isset($form)){ 
            $detailurl = 'https://api.jinshuju.net/v4/forms/'.$form.'?access_token='.$access_token;
            $select = json_decode(file_get_contents($detailurl),true);

            return json_encode($select['fields']);
          }
    }
}















