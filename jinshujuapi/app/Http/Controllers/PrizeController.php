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
        
        $me= session('email');
        $user = DB::table('users')->where('email',$me)
                                  ->get();

        $token = $user[0]->access_token;

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

                  	$counter = DB::table('customers')->where([
                                ['user',$me	],
                                ['form',$from]
                                ])->count();

                    DB::table('customers')->insert(
                    [ 
                      'user' => $me,
                      'form' => $from,
                      'uid' => $counter + 1,
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

                	$counter = DB::table('customers')->where([
                                ['user',$me	],
                                ['form',$from]
                                ])->count();

                    DB::table('customers')->insert(
                    [ 
                      'user' => $me,
                      'form' => $from,
                      'uid' => $counter + 1,
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
        $form = session('form');

        $customers = DB::table('customers')->orderBy('id')
                        ->where([
                                ['user',$me],
                                ['form',$form]
                                ])
                        ->get();

        $num = count($customers);

  //       foreach ($customers as $customer){
	 //      DB::table('customers')->where([
	 //                                ['user',$me],
	 //                                ['form',$from]
	 //                                ])
	 //                        ->update(['prize' => 0]);
		// }

        $prizes = DB::table('prizes')->orderBy('id')
			                        ->where([
			                                ['user',$me],
			                                ['form',$form]
			                                ])
			                        ->get();  

    foreach ($prizes as $prize){
            
      $remain = $prize->number;

			for($i=0; $i<$remain; $i++){

				$rand = mt_rand(1, $num)/$num;
				$chance = $prize->chance;

				if ($chance >= $rand && $remain >= 0){

					$winId = mt_rand(1, $num);

					$temp = DB::table('customers')        
					         ->where([
		                              ['user',$me],
		                              ['form',$form],
		                              ['uid',$winId]
		                             ])->get();

            if ($temp[0]->prize == 0){
					    DB::table('customers')        
					         ->where([
		                              ['user',$me],
		                              ['form',$form],
		                              ['uid',$winId]
		                             ])
					         ->update(['prize' => $prize->pid]);

					    $remain = $remain - 1;

					    DB::table('prizes')
					         ->where('id',$prize->id)
		                     ->update(['number' => $remain]);
		            }
                }
			}
		}

        $winners = DB::table('customers')->orderBy('id')->where([
                            ['user',$me],
                            ['form',$form]
                            ])->get();

        $result = array();
        $i=0;

        foreach ($winners as $winner){

        	if ( $winner->prize != 0){

        	  $prize = DB::table('prizes')->orderBy('id')
			                        ->where([
			                                ['user',$me],
			                                ['form',$form],
			                                ['pid',$winner->prize]
			                                ])
			                        ->get();

              $result[$i]= array(
              			   'name' => $winner->name,
              	           'phone'=> preg_replace("/(\d{3})(\d{4})(\d{4})/","$1****$3",$winner->phone),
              	           'prize'=> $prize[0]->name
              	           );
              $i=$i+1;
            }
        };

        return json_encode($result);
    }



}
