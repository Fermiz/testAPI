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

        $me= session('email');

        $this->prizes = DB::table('prizes')->orderBy('id')->where([
                            ['email',$me],
                            ])->get();

        return view('setting', ['me' => $me,'prizes' => $this->prizes
        ]);
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
        $email= session('email');
        $form= session('formid');

        $this->validate($request, [
            'name' => 'required|unique:prizes',
            'number' => 'required|integer|min:0',
            'chance' => 'required|min:0',
        ]);
        
        date_default_timezone_set('Asia/Shanghai');
        $current_time = Carbon::now();

        DB::table('prizes')->insert(
                    [ 
                      'email' => $email,
                      'form' => $form,
                      'name' => $request->name,
                      'number' => $request->number, 
                      'chance' => $request->chance,  
                      'created_at' => $current_time,
                      'updated_at' => $current_time 
                    ]
                    );

        return redirect('/settings');
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

        return redirect('/settings');
    }
}
