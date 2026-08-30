<?php

namespace App\Modules\Coupon\Controllers;

use App\Http\Controllers\BaseController;

use App\Modules\Coupon\Models\Coupon;
use App\Modules\Coupon\Models\CouponUsage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CouponController extends BaseController
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
        $coupons = Coupon::all();
        return view("Coupon::index", compact('coupons'));

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
        return view('Coupon::add');
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
            'code'=>'required | unique:coupons',
            'discount_value'=>'required|regex:/^[0-9]+(\.[0-9]{1,2})?$/',
            'discount_unit'=>'required',
            'start_date'=>'required',
            'application_id'=>'required',
            'user_type'=>'required',
            //'end_date'=>'required',
            //'uses_total'=>'required | numeric | min:1',
            'uses_total'=>'required | numeric ',
        ]);
        $request->request->add(['start_date'=> Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d H:i:s')]);
        if($request->end_date != null){
            $request->request->add(['end_date'=> Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d 23:59:59')]);
        }
        Coupon::create($request->all());
        $notification = array(
            'message' => 'Coupon has been added successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('coupons.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Coupon::find($id);
        return view("Coupon::show",compact('data'));
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
        $coupon = Coupon::find($id);
        return view('Coupon::edit', compact('coupon'));
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
            'code'=>'required | unique:coupons,code,'.$id,
            'discount_value'=>'required|regex:/^[0-9]+(\.[0-9]{1,2})?$/',
            'discount_unit'=>'required',
            'start_date'=>'required',
            'application_id'=>'required',
            'user_type'=>'required',
            //'end_date'=>'required',
            //'uses_total'=>'required | numeric | min:1',
            'uses_total'=>'required | numeric',
        ]);
        if (!$row =  Coupon::find($id)){
            $notification = array(
                'message' => 'Invalid request !!',
                'alert-type' => 'danger'
            );
            return redirect()->route('coupons.index')->with($notification);
        }
        $request->request->add(['start_date'=> Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d H:i:s')]);
        if($request->end_date != null) {
            $request->request->add(['end_date' => Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d 23:59:59')]);
        }
        $row->update($request->all());
        $notification = array(
            'message' => 'Coupon has been updated successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('coupons.index')->with($notification);
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
    public function verifyCoupon(Request $request){

        if($request){
            $validator = \Validator::make($request->all(), [
                'code' => 'required',
            ]);
            if($validator->fails()){
                return response()->json(['response'=>$validator->errors(),'message'=>'Invalid entry.','status'=>422]);
            }
            $coupon = Coupon::where('code','=',$request->code)
                ->where('start_date','<=',date('Y-m-d H:i:s'))
                ->where(function($q){
                    $q->where('end_date','=',null)
                        ->orWhere('end_date','>=',date('Y-m-d H:i:s'));
                })
                ->where(function($q1){
                    $q1->where('application_id','=',0)
                        ->orWhere('application_id','=',getAppDetailsForWeb()->id);
                })
                ->where(function($q2){
                    $q2->where('user_type','=',0)
                        ->orWhere(function ($q3){
                            $q3->where('user_type','=',1)
                                ->where('start_date','>',Auth::user()->created_at);
                        })->orWhere(function ($q4){
                            $q4->where('user_type','=',2)
                                ->where('start_date','<=',Auth::user()->created_at);
                        });
                })
                ->where('published' , 1)
                ->first();
            if($coupon){
                $total_coupon_usages = CouponUsage::where('coupon_id', '=', $coupon->id)->get();
                if (($coupon->uses_total != 0 && ($total_coupon_usages->count() < $coupon->uses_total)) || $coupon->uses_total == 0) {
                    $coupon_usage = CouponUsage::where('coupon_id','=',$coupon->id)->where('user_id','=',Auth::user()->id)->first();

                    if ($coupon_usage) {
                        return response()->json(['message' => 'Coupon is already used !!', 'status' => 407]);
                    } else {
                        return response()->json(['response' => $coupon, 'message' => 'success', 'status' => 200]);
                    }
                } else {
                    return response()->json(['message' => 'Invalid coupon', 'status' => 406]);
                }
            } else {
                return response()->json(['message' => 'Coupon Not Valid', 'status' => 404]);
            }

        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }
}
