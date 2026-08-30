<?php

namespace App\Modules\Application\Controllers;

use App\Modules\Agent\Models\Agent;
use App\Modules\Application\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class ApplicationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if(!in_array(Auth::user()->level_id ,[1,2,6])) {
            abort(403, 'Unauthorized action.');
        }
        $datas = Application::get();
        return view("Application::index", compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 )) {
            abort(403, 'Unauthorized action.');
        }

        return view("Application::create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required | email',
            'agent_id' => 'required',
            'package_name' => 'required',
            'domain_url' => 'required',
            'phone_number' => 'required',
        ]);

        DB::beginTransaction();
        try {
            if (\Illuminate\Support\Facades\Request::hasfile('application_logo')) {
                $file = $request->file('application_logo');
                $destinationPath = 'application';
                $fileName = date('Y-m-d-H-i-s') . $file->getClientOriginalName();
                $file->move($destinationPath, $fileName);
                $request->request->add(['logo'=>$fileName]);
            }

            $request_array = $request->all();
            if(!empty($request->dashboard_notice)){
                if (\Illuminate\Support\Facades\Request::hasfile('dashboard_notice_image')) {
                    $file1 = $request->file('dashboard_notice_image');
                    $destinationPath1 = 'application/notices';
                    $fileName1 = date('Y-m-d-H-i-s') . $file1->getClientOriginalName();
                    $file1->move($destinationPath1, $fileName1);
                    $request_array['notices']['dashboard']['image'] = url('/application/notices/'.$fileName1);
                }
                $request_array['notices']['dashboard']['notice'] = $request->dashboard_notice;
                $request_array['notices']['dashboard']['url'] = $request->dashboard_notice_url;
                $request_array['notices']['dashboard']['from'] = $request->dashboard_notice_from;
                $request_array['notices']['dashboard']['to'] = $request->dashboard_notice_to;
            }
            if(!empty($request->sendmoney_notice)) {

                if (\Illuminate\Support\Facades\Request::hasfile('sendmoney_notice_image')) {
                    $file2 = $request->file('sendmoney_notice_image');
                    $destinationPath2 = 'application/notices';
                    $fileName3 = date('Y-m-d-H-i-s') . $file2->getClientOriginalName();
                    $file2->move($destinationPath2, $fileName3);
                    $request_array['notices']['sendmoney']['image'] = url('/application/notices/'.$fileName3);
                }
                $request_array['notices']['sendmoney']['notice'] = $request->sendmoney_notice;
                $request_array['notices']['sendmoney']['url'] = $request->sendmoney_notice_url;
                $request_array['notices']['sendmoney']['from'] = $request->sendmoney_notice_from;
                $request_array['notices']['sendmoney']['to'] = $request->sendmoney_notice_to;
                $request_array['notices']['sendmoney']['sendmoney_disable'] = $request->sendmoney_disable == 'true' ? true : false;
            }
            $payment_info = [];
            foreach($request->type as $key => $type){
                if($type == 'Bank Transfer'){
                    $type = 'bank_transfer';
                }else{
                    $type = 'local_remit';
                }
                $payment_info[$type] = $type;
                $payment_info[$type]  = [];
                $payment_info[$type]['max'] = !empty($request->max[$key]) ? $request->max[$key] : 0;
                $payment_info[$type]['min'] =  !empty($request->min[$key]) ? $request->min[$key] : 0;
            }
            $request_array['payment_info'] = $payment_info;
            Application::create($request_array);

           // Flash::success(' Successfully Added');
            $notification = array(
                'message' => 'Distributor has been created successfully!',
                'alert-type' => 'success'
            );

        } catch (\Exception $e) {
            DB::rollback();
            $message = 'Sorry distributor could not be saved.';

            if (env('APP_DEBUG'))

                $message = $e->getMessage() . 'Line #' . $e->getLine();

          //  flash($message)->error()->important();
            $notification = array(
                'message' => 'Sorry application could not be saved!',
                'alert-type' => 'error'
            );

        }
        DB::commit();

        return redirect()->route('applications.index')->with($notification);


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!in_array(Auth::user()->level_id , [1,2,6])) {
            abort(403, 'Unauthorized action.');
        }
        $data = Application::find($id);

        return view("Application::show",compact('data'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 )) {
            abort(403, 'Unauthorized action.');
        }
        $data = Application::find($id);

        return view("Application::edit", compact('data'));
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

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required | email',
            'agent_id' => 'required',
            'package_name' => 'required',
            'domain_url' => 'required',
            'phone_number' => 'required',
            'pay_id' => 'nullable'
        ]);

        if (!$row =  Application::find($id)){
            $notification = array(
                'message' => 'Invalid request !!',
                'alert-type' => 'danger'
            );
            return redirect()->route('applications.index')->with($notification);
        }
        if (\Illuminate\Support\Facades\Request::hasfile('application_logo')) {
            $file = $request->file('application_logo');;
            $destinationPath = 'application';
            $fileName = date('Y-m-d-H-i-s') . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $request->request->add(['logo'=>$fileName]);

            if ($row->logo && file_exists('application/' . $row->logo))
                unlink('application/' . $row->logo);
        }
            $request_array = $request->all();
        if(!empty($request->dashboard_notice)) {
            if (\Illuminate\Support\Facades\Request::hasfile('dashboard_notice_image')) {

                $file1 = $request->file('dashboard_notice_image');;
                $destinationPath1 = 'application/notices';
                $fileName1 = date('Y-m-d-H-i-s') . $file1->getClientOriginalName();
                $file1->move($destinationPath1, $fileName1);
                $request_array['notices']['dashboard']['image'] = url('/application/notices/'.$fileName1);

                if((isset($row->notices['dashboard']) && !empty($row->notices['dashboard']['image'])) && file_exists('application/notices/' . $row->notices['dashboard']['image'])){
                    unlink($row->notices['dashboard']['image']);
                }
            }else{
                if(isset($row->notices['dashboard']) && !empty($row->notices['dashboard']['image'])){
                    $request_array['notices']['dashboard']['image'] = $row->notices['dashboard']['image'];
                }
            }
            $request_array['notices']['dashboard']['notice'] = $request->dashboard_notice;
            $request_array['notices']['dashboard']['url'] = $request->dashboard_notice_url;
            $request_array['notices']['dashboard']['from'] = $request->dashboard_notice_from;
            $request_array['notices']['dashboard']['to'] = $request->dashboard_notice_to;

        }
        if(!empty($request->sendmoney_notice)) {
            if (\Illuminate\Support\Facades\Request::hasfile('sendmoney_notice_image')) {
                $file2 = $request->file('sendmoney_notice_image');;
                $destinationPath2 = 'application/notices';
                $fileName2 = date('Y-m-d-H-i-s') . $file2->getClientOriginalName();
                $file2->move($destinationPath2, $fileName2);
                $request_array['notices']['sendmoney']['image'] = url('/application/notices/'.$fileName2);

                if((isset($row->notices['sendmoney']) && !empty($row->notices['sendmoney']['image'])) && file_exists('application/notices/' . $row->notices['sendmoney']['image'])){
                    unlink($row->notices['sendmoney']['image']);
                }
            }else{
                if(isset($row->notices['sendmoney']) && !empty($row->notices['sendmoney']['image'])){
                    $request_array['notices']['sendmoney']['image'] = $row->notices['sendmoney']['image'];
                }
            }
            $request_array['notices']['sendmoney']['notice'] = $request->sendmoney_notice;
            $request_array['notices']['sendmoney']['url'] = $request->sendmoney_notice_url;
            $request_array['notices']['sendmoney']['from'] = $request->sendmoney_notice_from;
            $request_array['notices']['sendmoney']['to'] = $request->sendmoney_notice_to;
            $request_array['notices']['sendmoney']['sendmoney_disable'] = $request->sendmoney_disable == 'true' ? true : false;
        }

        $payment_info = [];
        foreach($request->type as $key => $type){
            if($type == 'Bank Transfer'){
                $type = 'bank_transfer';
            }else{
                $type = 'local_remit';
            }
            $payment_info[$type] = $type;
            $payment_info[$type]  = [];
            $payment_info[$type]['max'] = !empty($request->max[$key]) ? $request->max[$key] : 0;
            $payment_info[$type]['min'] =  !empty($request->min[$key]) ? $request->min[$key] : 0;
        }
        $request_array['payment_info'] = $payment_info;

        $row->update($request_array);
        $notification = array(
            'message' => 'Application has been updated successfully!',
            'alert-type' => 'success'
        );
       // flash('successfully updated')->success()->important();
        return redirect()->route('applications.index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$row =  Application::find($id)){
            $notification = array(
                'message' => 'Invalid request !!',
                'alert-type' => 'danger'
            );
            return redirect()->route('applications.index')->with($notification);
        }
        if ($row->logo && file_exists('application/' . $row->logo)){
            unlink('application/' . $row->logo);
        }
        $row->delete();

        $notification = array(
            'message' => 'Application has been deleted successfully!',
            'alert-type' => 'success'
        );
        // flash('successfully updated')->success()->important();
        return redirect()->route('applications.index')->with($notification);

    }
}
