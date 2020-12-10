<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Institution;

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
        if(!auth()->check()) {
            $institution = Institution::where('prefix', $prefix)->first();
            if($institution) {
                return view('auth.login', compact('prefix'));
            } else {
                
                if($prefix == 'admin') { // For TB super admin
                    return view('auth.login', compact('prefix'));
                } else {
                    $message = 'Not found matched Institution';
                    return view('welcome', compact('message'));
                }
            }
        } else {
            return redirect()->route('admin.dashboard');
        }
        
    }
}
