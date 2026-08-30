<?php

namespace App\Modules\EmailTemplate\Controllers;

use App\Http\Controllers\BaseController;
use App\Modules\EmailTemplate\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailTemplateController extends BaseController
{
    protected $rules = [
        'application_id' => 'required',
        'type' => 'required',
        'subject' => 'required',
        'message' => 'required',
        'active' => ['nullable','boolean']
    ];
    function __construct(EmailTemplate $emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = $this->emailTemplate->get();
        return view("EmailTemplate::index",compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['application_list'] = getApplicationList();
        $data['application_list'][0] = 'Default';
        ksort($data['application_list']);
        return view('EmailTemplate::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->rules['type'] = 'required | unique:email_templates,type,NULL,id,application_id,'.$request->application_id;
        $this->validate($request,$this->rules);
        $email_template = $this->emailTemplate->add($request->all());
        if($email_template){
            $notification = array(
                'message' => 'Email Template been added successfully!',
                'alert-type' => 'success'
            );
            return redirect()->route('email-template.index')->with($notification);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['template'] = EmailTemplate::find($id);
        $data['application_list'] = getApplicationList();
        $data['application_list'][0] = 'Default';
        ksort($data['application_list']);
        return view('EmailTemplate::edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->rules['type'] = 'required';
        $this->validate($request,$this->rules);
        $email_template = $this->emailTemplate->edit($request->all(),$id);
        if($email_template){
            $notification = array(
                'message' => 'Email Template updated successfully!',
                'alert-type' => 'success'
            );
            return redirect()->route('email-template.index')->with($notification);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
