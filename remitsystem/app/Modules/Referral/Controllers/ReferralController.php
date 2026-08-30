<?php

namespace App\Modules\Referral\Controllers;

use App\Http\Controllers\BaseController;
use App\Modules\Referral\Models\Referral;
use App\Modules\Referral\Models\ReferralPoints;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReferralController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(Referral $referral,ReferralPoints $referral_points)
    {
        $this->referral = $referral;
        $this->referral_points = $referral_points;
        parent::__construct();
    }
    public function index()
    {
       // dd('1');
        if (Auth::user()->level_id == 3) {
            abort(403,'Forbidden');
        }
        $my_referral = Auth::user()->referral_code;

        $referral_points = $this->referral_points->getReferralPoints(Auth::user()->id);
        $referral_counts = $this->referral->getReferralCounts(Auth::user()->id);
        return view("Referral::index",compact('my_referral','referral_points','referral_counts'));
    }

    public function getReferrals(Request $request){
        $referrals =  $this->referral->getReferral();

        if(!in_array(Auth::user()->level_id, [1,2])){
            $referrals = $referrals->where('referrer_id',Auth::user()->id);
        }

        $datatable =  DataTables::of($referrals)
            ->editColumn('id', function ($data) {
                return format_id($data->id, "R");
            })
            ->editColumn('status',function($data){
                $status = '';
                if($data->status == 0){
                    $status = '<span class="badge badge-blue">Pending</span>';
                }elseif($data->status == 1){
                    $status = '<span class="badge badge-success">Approved</span>';
                }
                return $status;
            })->editColumn('created_at',function($data){
                return standard_date($data->created_at);
            })->rawColumns(['status']);
        if ($keyword = $request->get('search')['value']) {
            $datatable->filterColumn('referrer', function ($query, $keyword) {
                $sql = 'CONCAT_WS(" ", P1.first_name, NULLIF(P1.middle_name,""), P1.last_name) like ?';
                $query->whereRaw($sql, ["%{$keyword}%"]);
            });
            $datatable->filterColumn('client_name', function ($query, $keyword) {
                $sql = 'CONCAT_WS(" ", P2.first_name, NULLIF(P2.middle_name,""), P2.last_name) like ?';
                $query->whereRaw($sql, ["%{$keyword}%"]);
            });
        }
        return $datatable->make(true);
    }



}
