<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    // Landing page for Institution
    public function index()
    {
        return view('welcome');
    }

    // Login for Institution
    public function login($prefix)
    {
        $institution = DB::table('institutions')->where('prefix', $prefix)->first();
        if($institution) {
            return view('auth.login', compact('prefix'));
        } else {
            $message = 'Not found matched Institution';
            return view('welcome', compact('message'));
        }
    }
}
