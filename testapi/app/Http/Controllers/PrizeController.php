<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
        $this->middleware('auth');

        $this->prizes = $prizes;
    }
    

    /**
     * Display a list of all of the user's task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        return view('prizes.index', [
            'prizes' => $this->prizes->forUser($request->user()),
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
	    $this->validate($request, [
	        'name' => 'required|max:255|unique:prizes',
	        'number' => 'required|min:0',
	    ]);

	    // Create The Prize...
	     $request->user()->prizes()->create([
	        'name' => $request->name,
	        'number' => $request->number,
	    ]);

	    return redirect('/prizes');
	}

   /**
	 * Destroy the given prize.
	 *
	 * @param  Request  $request
	 * @param  Prize $prize
	 * @return Response
	 */
	public function destroy(Request $request, Prize $prize)
	{
	    $this->authorize('destroy', $prize);

		$prize->delete();

	    return redirect('/prizes');
	}

}