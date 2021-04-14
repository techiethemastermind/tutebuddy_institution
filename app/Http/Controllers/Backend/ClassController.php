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
        // $this->middleware('permission:class_access', ['only' => ['index', 'show']]);
        // $this->middleware('permission:class_create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:class_edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:class_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classes_count = Grade::all()->count();
        return view('backend.classes.index', compact('classes_count'));
    }

    /** Get Table Data **/
    public function getTableData()
    {
        if(auth()->user()->hasRole('Teacher')) {
            $class_ids = DB::table('curriculum')->where('user_id', auth()->user()->id)->pluck('class_id');
            $classes = Grade::whereIn('id', $class_ids)->get();
        } else {
            $classes = Grade::all();
        }

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
            $delete_route = route('admin.classes.destroy', $class->id);

            $btn_edit = view('layouts.buttons.edit', ['edit_route' => $edit_route])->render();
            $btn_delete = view('layouts.buttons.delete', ['delete_route' => $delete_route])->render();

            $temp['action'] = $btn_edit . '&nbsp;' . $btn_delete;

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /** create a new class */
    public function createClass(Request $request)
    {
        $data = $request->all();
        $class_data = [
            'name' => $data['name'],
            'value' => $data['value'] - 1,
            'type' => $data['class_type'],
            'institution_id' => auth()->user()->institution_id
        ];

        $class = Grade::create($class_data);
        
        return response()->json([
            'success' => true
        ]);
    }

    /** Edit Class Management */
    public function edit($id)
    {
        $class = Grade::find($id);
        $courses = Course::pluck('title', 'id')->all();
        $divisions = Division::pluck('name', 'id')->all();
        $students = [];
        if(!auth()->user()->hasRole('Administrator')) {
            $student_objs = User::role('Student')->where('institution_id', auth()->user()->institution->id)->get();
        } else {
            $student_objs = User::role('Student')->get();
        }

        foreach($student_objs as $obj) {
            $temp = [$obj->id => $obj->fullName()];
            $students += $temp;
        }
        
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
        // Remove existing divisions
        DB::table('class_division')->where('grade_id', $class_id)->delete();

        // Insert new divisions
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

        // Remove existing students
        DB::table('class_user')->where('grade_id', $class_id)->delete();

        // Insert new students
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

    /** Generate Standard Classes for Institution */
    public function generate()
    {
        $institution_id = auth()->user()->institution_id;
        // For Grade
        for($i = 0; $i < 10; $i++) {
            $data = [
                'name' => 'Grade' . ($i + 1),
                'value' => $i,
                'type' => 'grade',
                'institution_id' => $institution_id
            ];

            Grade::create($data);
        }

        // For College
        for($i = 0; $i < 2; $i++) {
            $data = [
                'name' => 'College' . ($i + 1),
                'value' => $i,
                'type' => 'college',
                'institution_id' => $institution_id
            ];

            Grade::create($data);
        }

        // For Graduation
        for($i = 0; $i < 3; $i++) {
            $data = [
                'name' => 'Graduation' . ($i + 1),
                'value' => $i,
                'type' => 'graduation',
                'institution_id' => $institution_id
            ];

            Grade::create($data);
        }

        // Add divisions
        $divisions = ['A', 'B', 'C', 'D'];
        foreach($divisions as $division)
        {
            Division::create([
                'institution_id' => $institution_id,
                'name' => $division
            ]);
        }

        $classes = Grade::where('institution_id', $institution_id)->get();
        $divisions = Division::where('institution_id', $institution_id)->get();
        foreach($classes as $class)
        {
            foreach($divisions as $division) {
                $insert_data = [
                    'grade_id' => $class->id,
                    'division_id' => $division->id
                ];
                DB::table('class_division')->insert($insert_data);
            }
        }

        return back()->with('success', 'Successfully Generated');
    }

    /** delete a class */
    public function destroy($id)
    {
        try {
            Grade::find($id)->delete();

            return response()->json([
                'success' => true,
                'action' => 'destroy'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
