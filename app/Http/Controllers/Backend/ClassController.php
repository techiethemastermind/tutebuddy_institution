<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Grade;
use App\Models\Course;
use App\Models\Division;
use App\User;

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
                                            <small class="text-70">'. $class->institution->name .'</small>
                                        </div>
                                    </div>
                                </div>';

            $temp['subjects'] = $class->courses->count();

            if($class->divisions->count() > 0) {
                $temp['divisions'] = $class->divisions->count();
            } else {
                $temp['divisions'] = 'N/A';
            }

            $temp['students'] = $class->students->count();

            $edit_route = route('admin.classes.edit', $class->id);
            $temp['action'] = view('layouts.buttons.edit', ['edit_route' => $edit_route]) . '&nbsp;';

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /** Edit Class Management */
    public function edit($id)
    {
        $class = Grade::find($id);
        $courses = Course::pluck('title', 'id')->all();
        $divisions = Division::pluck('name', 'id')->all();
        $students = User::role('Student')->pluck('name', 'id')->all();
        return view('backend.classes.edit', compact('class', 'courses', 'divisions', 'students'));
    }

    /** Update Class Data */
    public function update(Request $request, $id)
    {
        $class = Grade::find($id);
        $data = $request->all();
        $class->name = $data['name'];
        $class_id = $class->id;

        if(isset($data['course'])) {
            Course::whereIn('id', $data['course'])->update([
                'class_id' => $class_id
            ]);
        }

        if(isset($data['division'])) {
            foreach ($data['division'] as $item) {
                DB::table('class_division')->updateOrInsert(
                    [
                        'grade_id' => $class_id,
                        'division_id' => $item
                    ],
                    [
                        'grade_id' => $class_id,
                        'division_id' => $item
                    ]
                );
            }
        }

        if(isset($data['students'])) {
            foreach ($data['students'] as $item) {
                DB::table('class_user')->updateOrInsert(
                    [
                        'grade_id' => $class_id,
                        'user_id' => $item
                    ],
                    [
                        'grade_id' => $class_id,
                        'user_id' => $item
                    ]
                );
            }
        }

        return back()->with('success', 'successfully updated');
    }
}
