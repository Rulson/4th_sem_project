<?php

namespace App\Modules\Slider\Controllers;

use App\Http\Controllers\BaseController;

use App\Modules\Slider\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;


class SliderController extends BaseController
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
        $sliders = Slider::all();
        return view("Slider::index", compact('sliders'));

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

        return view('Slider::add');
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
            'application_id'=>'required',
            'slider_image' => 'required',
            'sort_order' => 'required',
        ]);
        if(\Illuminate\Support\Facades\Request::hasFile('slider_image')){
            $destinationPath = 'sliders';
            $file = $request->slider_image;
            $fileName = date('Y-m-d-H-i-s') . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $request->request->add(['image'=>$fileName]);
        }
        Slider::create($request->all());
        $notification = array(
            'message' => 'Slider has been added successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('sliders.index')->with($notification);
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
        $slider = Slider::find($id);

        return view('Slider::edit', compact('slider'));
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
            'application_id'=>'required',
            'sort_order'=>'required'
        ]);
        $data = Slider::find($id);
        if(\Illuminate\Support\Facades\Request::hasFile('slider_image')){
            $destinationPath = 'sliders';
            $file = $request->slider_image;
            $fileName = date('Y-m-d-H-i-s') . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $request->request->add(['image'=>$fileName]);

            //remove existing image
            if ($data->image && file_exists('sliders/' . $data->image))
                unlink('sliders/' . $data->image);
        }
        $data->update($request->all());
        $notification = array(
            'message' => 'Slider has been updated successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('sliders.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::find($id);

        if(!$slider){
            $notification = array(
                'message' => 'Slider can not delete!',
                'alert-type' => 'success'
            );
            return redirect()->back()->with('notification',$notification);
        }
        //remove existing image
        if ($slider->image && file_exists('sliders/' . $slider->image))
            unlink('sliders/' . $slider->image);

        $slider->delete();
        $notification = array(
            'message' => 'Slider deleted successfully!',
            'alert-type' => 'success'
        );
        return redirect()->back()->with('notification',$notification);
    }

}
