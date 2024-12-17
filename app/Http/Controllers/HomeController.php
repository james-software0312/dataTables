<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    /////// Dashboard
    public function index() 
    {
        $user = Auth::user();
    
        
        return view('page.article', compact('user'));
    }
}
