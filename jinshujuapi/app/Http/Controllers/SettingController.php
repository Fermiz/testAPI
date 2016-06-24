<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Socialite;
use Carbon\Carbon;
use App\Prize;
use App\Http\Requests;

class SettingController extends Controller
{
    /**
     * The prize repository instance.
     *
     * @var prizeRepository
     */
    protected $prizes;

    /**
     * Display a list of all of the user's prize.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        Socialite::with('jinshuju')->refresh();
        $formid = $request->form;
        $nameid = $request->name;
        $phoneid = $request->phone;

        $me= session('email');
        session(['form'=> $formid]);
        session(['name'=> $nameid]);
        session(['phone'=> $phoneid]);

        $this->prizes = DB::table('prizes')->orderBy('id')->where([
                            ['user',$me],
                            ['form',$formid]
                            ])->get();

        return view('setting', ['me' => $me,'prizes' => $this->prizes
        ]);
    }

    /**
     * Display a list of all of the user's prize.
     *
     * @param  Request  $request
     * @return Response
     */
    public function reset(Request $request)
    {
        Socialite::with('jinshuju')->refresh();
        
        $me= session('email');
        $formid = session('form');

        $this->prizes = DB::table('prizes')->orderBy('id')->where([
                            ['user',$me],
                            ['form',$formid]
                            ])->get();
        
        return view('setting', ['me' => $me,'prizes' => $this->prizes]);
       
    }

    /**
     * Create a new prize.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        Socialite::with('jinshuju')->refresh();
        $me= session('email');
        $form= session('form');

        $this->validate($request, [
            'name' => 'required',
            'number' => 'required|integer|min:0',
            'chance' => 'required|numeric|min:0|max:1',
        ]);

        $pid = DB::table('prizes')->orderBy('id')
			                        ->where([
    	                                ['user',$me],
    	                                ['form',$form]
	                                ])->count(); 

        date_default_timezone_set('Asia/Shanghai');
        $current_time = Carbon::now();

        DB::table('prizes')->insert(
                    [ 
                      'user' => $me,
                      'form' => $form,
                      'pid' => $pid + 1,
                      'name' => $request->name,
                      'number' => $request->number, 
                      'chance' => $request->chance,  
                      'created_at' => $current_time,
                      'updated_at' => $current_time 
                    ]
                    );

        return redirect('/reset');
    }

    /**
     * Destroy the given prize.
     *
     * @param  Request  $request
     * @param  prize  $prize
     * @return Response
     */
    public function destroy(Request $request)
    {
        $prizeId = $request->prizeid;
        DB::table('prizes')->where([
                                  ['id',$prizeId],
                                 ])->delete();

        return redirect('/reset');
    }
}
