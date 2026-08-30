<?php

namespace App\Modules\Auth\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("Auth::index");
    }
}
