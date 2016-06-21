<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Socialite;
use Carbon\Carbon;
use App\Http\Requests;

class PrizeController extends Controller
{
    
    public function index(Request $request)
    {

        $formid = $request->form;
        $nameid = $request->name;
        $phoneid = $request->phone;
        $me = $request->me;

        $token= session('access_token');
        date_default_timezone_set('Asia/Shanghai');
        $current_time = Carbon::now();

        //获取数据
        if(isset($formid)){ 
            
            $data = \Socialite::with('jinshuju')->getDataByToken($formid,$token);

            $count = count($data);
            $i = 0;
            
            for($i=0; $i<$count; $i++){        
              
              if (is_array($data[$i]["$phoneid"])){
                  
                  if($data[$i]["$phoneid"]['value'] == "" || $data[$i]["$nameid"] == ""){
                     continue;
                  }

                  $search = DB::table('users')->where([
                                ['user',$me],
                                ['form',$formid],
                                ['phone',$data[$i]["$phoneid"]['value']]
                                ])->count();
                  if($search == 0){
                    DB::table('users')->insert(
                    [ 
                      'user' => $me,
                      'form' => $formid,
                      'name' => $data[$i]["$nameid"], 
                      'phone' => $data[$i]["$phoneid"]['value'],  
                      'created_at' => $current_time,
                      'updated_at' => $current_time 
                    ]
                    );
                  }
              }else{
                
                if($data[$i]["$phoneid"] == "" || $data[$i]["$nameid"] == ""){
                    continue;
                }

                $search = DB::table('users')->where([
                            ['user',$me],
                            ['form',$formid],
                            ['phone',$data[$i]["$phoneid"]]
                            ])->count();

                if($search == 0){
                    DB::table('users')->insert(
                    [ 
                      'user' => $me,
                      'form' => $formid,
                      'name' => $data[$i]["$nameid"], 
                      'phone' => $data[$i]["$phoneid"],  
                      'created_at' => $current_time,
                      'updated_at' => $current_time 

                    ]
                    );
                  }
              }
              
            }
          }

        $users = DB::table('users')->orderBy('id')->where([
                            ['user',$me],
                            ['form',$formid]
                            ])->get();

        return view('prizes',['me' => $me,'form' =>$formid,'users' => $users]);
    
    }

    public function winner(Request $request)
    {
        $me = $request->me;
        $formid = $request->form;
        $chance = 10;
        $users = DB::table('users')->orderBy('id')
                        ->where([
                                ['user',$me],
                                ['form',$formid]
                                ])
                        ->get();

        
        foreach ($users as $user){
            $randNum = mt_rand(0, $chance);
            DB::table('users')
                     ->where('id', $user->id)
                     ->update(['won' => false]);
            if($randNum == 0){
                DB::table('users')
                     ->where('id', $user->id)
                     ->update(['won' => true]);
            }
        };

        $winners = DB::table('users')->orderBy('id')->where([
                            ['user',$me],
                            ['form',$formid],
                            ['won',true]
                            ])->get();


        foreach ($winners as &$winner){
             $winner->phone = preg_replace("/(\d{3})(\d{4})(\d{4})/","$1****$3",$winner->phone);
        };

        return json_encode($winners);
    }



}
