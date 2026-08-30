<?php

namespace App\Modules\Settings\Controllers;

use App\Modules\Settings\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class SettingsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 )) {
            abort(403, 'Unauthorized action.');
        }
        $data = Settings::first();
        return view("Settings::edit", compact('data'));
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
        return view("Settings::create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $formData['company_name'] = $request['company_name'];
            $formData['abn'] = $request['abn'];
            if(\Illuminate\Support\Facades\Request::file('logo'))
            {
                $file=\Illuminate\Support\Facades\Request::file('logo');
                $uniqueid = uniqid();
                $destinationPath='storage/logo';
                $file->move($destinationPath,$uniqueid.$file->getClientOriginalName());
                $formData['logo'] = $uniqueid.$file->getClientOriginalName();
            }
            $formData['phone_number'] = $request['phone_number'];
            $formData['phone_number'] = $request['phone_number'];
            $formData['email_address'] = strtolower($request['email_address']);
            $formData['street'] = $request['street'];
            $formData['suburb'] = $request['suburb'];
            $formData['state'] = $request['state'];
            $formData['postcode'] = $request['postcode'];
            $formData['country'] = $request['country'];
            $formData['account_name'] = $request['account_name'];
            $formData['account_no'] = $request['account_no'];
            $formData['bsb'] = $request['bsb'];
            $formData['bank_name'] = $request['bank_name'];
            $formData['description'] = $request['description'];
            $formData['service_charge'] = $request['service_charge'];
            Settings::create($formData);
            flash('successfully added')->success()->important();

        } catch (\Exception $e) {
            DB::rollback();
            $message = 'Sorry agent could not be saved.';

            if (env('APP_DEBUG'))

                $message = $e->getMessage() . 'Line #' . $e->getLine();

            flash($message)->error()->important();

        }
        DB::commit();

        return redirect()->route('settings.index');


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
        $settings = Settings::find($id);
        DB::beginTransaction();
        try {
            $settings['company_name'] = $request['company_name'];
            $settings['abn'] = $request['abn'];
            if(\Illuminate\Support\Facades\Request::file('logo'))
            {
                $file=\Illuminate\Support\Facades\Request::file('logo');
                $uniqueid = uniqid();
                $destinationPath='storage/logo';
                $file->move($destinationPath,$uniqueid.$file->getClientOriginalName());
                $settings['logo'] = $uniqueid.$file->getClientOriginalName();
            }
            $settings['phone_number'] = $request['phone_number'];
            $settings['phone_number'] = $request['phone_number'];
            $settings['email_address'] = strtolower($request['email_address']);
            $settings['street'] = $request['street'];
            $settings['suburb'] = $request['suburb'];
            $settings['state'] = $request['state'];
            $settings['postcode'] = $request['postcode'];
            $settings['country'] = $request['country'];
            $settings['account_name'] = $request['account_name'];
            $settings['account_no'] = $request['account_no'];
            $settings['bsb'] = $request['bsb'];
            $settings['bank_name'] = $request['bank_name'];
            $settings['description'] = $request['description'];
            $settings['service_charge'] = $request['service_charge'];
            $settings->save();
            flash('Successfully Updated')->success()->important();

        } catch (\Exception $e) {
            DB::rollback();
            $message = 'Sorry agent could not be saved.';

            if (env('APP_DEBUG'))

                $message = $e->getMessage() . 'Line #' . $e->getLine();

            flash($message)->error()->important();

        }
        DB::commit();

        return redirect()->back();


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
