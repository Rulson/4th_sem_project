<?php

namespace App\Modules\State\Controllers;

use App\Http\Controllers\BaseController;

use App\Modules\User\Models\AusStates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class StateController extends BaseController
{


    function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!in_array(Auth::user()->level_id,[1,2,3,4,6])) {
            abort(403, 'Unauthorized action.');
        }
        $states = AusStates::all();
        return view("State::index", compact('states'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!in_array(Auth::user()->level_id ,[1,2,3])) {
            abort(403, 'Unauthorized action.');
        }
        $data['state'] = AusStates::select('id','name')->whereIn('type',['np_state','au_state'])->pluck('name','id');

        return view('State::add',compact('data'));
    }

    /*
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'type'=>'required',
        ]);
        AusStates::create(['name'=>$request->name,'type'=>$request->type,'parent_id'=>$request->parent_id,
        'postcode'=>$request->postcode]);
        $notification = array(
            'message' => 'State/District has been added successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('states.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


        return view("Sender::show", compact('allAddress', 'allIdentifications', 'sender', 'sender_beneficiary', 'sender_status', 'transaction_count', 'beneficiary', 'transaction', 'transactionData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!in_array(Auth::user()->level_id, [1,2,3])) {
            abort(403, 'Unauthorized action.');
        }
        $state = AusStates::find($id);
        $data['state'] = AusStates::select('id','name')->whereIn('type',['np_state','au_state'])->pluck('name','id');


        return view('State::edit', compact('state','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'=>'required',
            'type'=>'required'
        ]);
        AusStates::where('id',$id)->update([
            'name'=>$request->name,
            'type'=>$request->type,
            'parent_id'=>$request->parent_id,
            'postcode' => $request->postcode
        ]);
        $notification = array(
            'message' => 'State/District has been updated successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('states.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
