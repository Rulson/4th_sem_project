<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogOutController extends Controller
{
    public function getLogout(){
        Auth::logout();
        return redirect('login');
    }
}
