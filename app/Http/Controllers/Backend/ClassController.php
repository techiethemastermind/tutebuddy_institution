<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;

class ClassController extends Controller
{
    /**
     * Constructor of the resource.
     */
    function __construct()
    {
        // Define Permission
        $this->middleware('permission:class_access', ['only' => ['index', 'show']]);
        $this->middleware('permission:class_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:class_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:class_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.classes.index');
    }

    /** Get Table Data **/
    public function getTableData()
    {
    	$classes = Grade::all();
    	$data = [];
        foreach ($classes as $class) {
            $temp = [];
            $temp['index'] = '';
            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        <span class="avatar-title rounded bg-primary text-white">
                                            '. substr($class->name, 0, 2) .'
                                        </span>
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex flex-column">
                                            <small class="js-lists-values-project">
                                                <strong>'. $class->name .'</strong>
                                            </small>
                                            <small class="text-70"></small>
                                        </div>
                                    </div>
                                </div>';


            $temp['divisions'] = '2';
            $temp['students'] = '100';
            $temp['action'] = 'action';

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
