<?php

namespace App\Http\Controllers;

class WebsiteController extends Controller
{
    public function termsAndConditions()
    {
        return view('frontend.terms_and_conditions');
    }
}
