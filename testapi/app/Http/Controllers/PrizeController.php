<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Carbon\Carbon;
use App\Prize;
use App\Repositories\PrizeRepository;

class PrizeController extends Controller
{
    /**
     * The task repository instance.
     *
     * @var TaskRepository
     */
    protected $prizes;

    /**
     * Create a new controller instance.
     *
     * @param  TaskRepository  $prizes
     * @return void
     */
    public function __construct(PrizeRepository $prizes)
    {
        //$this->middleware('auth');

        //$this->prizes = $prizes;
    }
    

    /**
     * Display a list of all of the user's task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        // return view('prizes.index', [
        //     'prizes' => $this->prizes->forUser($request->user()),
        // ]);
        $formid = $request->form;
        $nameid = $request->name;
        $phoneid = $request->phone;
        $access_token= $request->access_token;
        $me = $request->me;

        $token = DB::table('tokens')->where('access_token', $access_token)->first();
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
        }
        
        //获取数据
        if(isset($formid)){ 
            $dataurl = 'https://api.jinshuju.net/v4/forms/'.$formid.'/entries?access_token='.$token->access_token;
            $data = json_decode(file_get_contents($dataurl),true);

            $count = count($data);
            $i = 0;
            
            for($i=0; $i<$count; $i++){        
              
              if (is_array($data[$i]["$phoneid"])){
                  
                  if($data[$i]["$phoneid"]['value'] == "" || $data[$i]["$nameid"] == ""){
                     continue;
                  }

                  $search = DB::table('customers')->where([
                                ['user',$me],
                                ['form',$formid],
                                ['phone',$data[$i]["$phoneid"]['value']]
                                ])->count();
                  if($search == 0){
                    DB::table('customers')->insert(
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

                $search = DB::table('customers')->where([
                            ['user',$me],
                            ['form',$formid],
                            ['phone',$data[$i]["$phoneid"]]
                            ])->count();

                if($search == 0){
                    DB::table('customers')->insert(
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

        $customers = DB::table('customers')->orderBy('id')->where([
                            ['user',$me],
                            ['form',$formid]
                            ])->get();

        return view('prizes',['me' => $me,'form' =>$formid,'customers' => $customers]);
    }
    
    public function winner(Request $request)
    {
        $me = $request->me;
        $formid = $request->form;
        $chance = 10;
        $customers = DB::table('customers')->orderBy('id')
                        ->where([
                                ['user',$me],
                                ['form',$formid]
                                ])
                        ->get();

        
        foreach ($customers as $customer){
            $randNum = mt_rand(0, $chance);
            DB::table('customers')
                     ->where('id', $customer->id)
                     ->update(['won' => false]);
            if($randNum == 0){
                DB::table('customers')
                     ->where('id', $customer->id)
                     ->update(['won' => true]);
            }
        };

        $winners = DB::table('customers')->orderBy('id')->where([
                            ['user',$me],
                            ['form',$formid],
                            ['won',true]
                            ])->get();


        foreach ($winners as &$winner){
             $winner->phone = preg_replace("/(\d{3})(\d{4})(\d{4})/","$1****$3",$winner->phone);
        };

        return json_encode($winners);
    
    }
 //    /**
	//  * Create a new prize.
	//  *
	//  * @param  Request  $request
	//  * @return Response
	//  */
	// public function store(Request $request)
	// {
	//     $this->validate($request, [
	//         'name' => 'required|max:255|unique:prizes',
	//         'number' => 'required|min:0',
	//     ]);

	//     // Create The Prize...
	//      $request->user()->prizes()->create([
	//         'name' => $request->name,
	//         'number' => $request->number,
	//     ]);

	//     return redirect('/prizes');
	// }

 //   /**
	//  * Destroy the given prize.
	//  *
	//  * @param  Request  $request
	//  * @param  Prize $prize
	//  * @return Response
	//  */
	// public function destroy(Request $request, Prize $prize)
	// {
	//     $this->authorize('destroy', $prize);

	// 	$prize->delete();

	//     return redirect('/prizes');
	// }

}