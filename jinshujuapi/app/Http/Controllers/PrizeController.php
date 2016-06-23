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
    	Socialite::with('jinshuju')->refresh();
        
        $token = session('access_token');
        $me= session('email');
        $from = session('form');
        $nameid = session('name');
        $phoneid = session('phone');


        date_default_timezone_set('Asia/Shanghai');
        $current_time = Carbon::now();

        //获取数据
        if(isset($from)){ 
            
            $data = Socialite::with('jinshuju')->getDataByToken($from,$token);

            $count = count($data);
            
            for($i=0; $i<$count; $i++){        
              
              if (is_array($data[$i]["$phoneid"])){
                  
                  if($data[$i]["$phoneid"]['value'] == "" || $data[$i]["$nameid"] == ""){
                     continue;
                  }

                  $search = DB::table('customers')->where([
                                ['user',$me	],
                                ['form',$from],
                                ['phone',$data[$i]["$phoneid"]['value']]
                                ])->count();
                  if($search == 0){
                    DB::table('customers')->insert(
                    [ 
                      'user' => $me,
                      'form' => $from,
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

                $search = DB::table('customers')->where([
                            ['user',$me],
                            ['form',$from],
                            ['phone',$data[$i]["$phoneid"]]
                            ])->count();

                if($search == 0){
                    DB::table('customers')->insert(
                    [ 
                      'user' => $me,
                      'form' => $from,
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

        $customers = DB::table('customers')->orderBy('id')->where([
                            ['user',$me],
                            ['form',$from]
                            ])->get();

        return view('prizes',['me' => $me,'form' =>$from,'customers' => $customers]);
    
    }

    public function winner(Request $request)
    {
        $me= session('email');
        $from = session('form');

        $customers = DB::table('customers')->orderBy('id')
                        ->where([
                                ['user',$me],
                                ['form',$from]
                                ])
                        ->get();

        $chance = count($customers);

        foreach ($customers as $customer){
	      DB::table('customers')->orderBy('id')
	                        ->where([
	                                ['user',$me],
	                                ['form',$from]
	                                ])
	                        ->update(['prize' => 0]);
		}

        $prizes = DB::table('prizes')->orderBy('id')
			                        ->where([
			                                ['user',$me],
			                                ['form',$from]
			                                ])
			                        ->get();  

        foreach ($prizes as $prize){
            
            $max = $chance*$prize->chance;

			for($i=0; $i< $max; $i++){  

				$winId = mt_rand(0, $chance);

			    DB::table('customers')
			         ->where('id', $winId)
			         ->update(['prize' => $prize->id]);

			}
		}

        $winners = DB::table('customers')->orderBy('id')->where([
                            ['user',$me],
                            ['form',$from],
                            ['prize',2]
                            ])->get();


        foreach ($winners as &$winner){
             $winner->phone = preg_replace("/(\d{3})(\d{4})(\d{4})/","$1****$3",$winner->phone);
        };

        return json_encode($winners);
    }



}
