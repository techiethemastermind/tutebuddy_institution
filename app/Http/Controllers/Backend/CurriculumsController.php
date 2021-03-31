<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Division;

class CurriculumsController extends Controller
{
    /**
     * Constructor of the resource.
     */
    // function __construct()
    // {
    //     // Define Permission
    //     $this->middleware('permission:curriculum_access', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:curriculum_create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:curriculum_edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:curriculum_delete', ['only' => ['destroy']]);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Course::all();
        return view('backend.curriculums.index', compact('subjects'));
    }
}
