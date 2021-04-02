<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $grades = Grade::pluck('name', 'id')->all();

        $teachers = [];
        if(!auth()->user()->hasRole('Administrator')) {
            $teacher_objs = User::role('Teacher')->where('institution_id', auth()->user()->institution->id)->get();
        } else {
            $teacher_objs = User::role('Teacher')->get();
        }

        foreach($teacher_objs as $obj) {
            $temp = [$obj->id => $obj->fullName()];
            $teachers += $temp;
        }

        return view('backend.curriculums.index', compact('subjects', 'grades', 'teachers'));
    }

    /** Get Tabel Data */
    public function getCurriculumsTableData()
    {
        $subjects = Course::all();
        $grades = Grade::pluck('name', 'id')->all();

        $teachers = [];
        if(!auth()->user()->hasRole('Administrator')) {
            $teacher_objs = User::role('Teacher')->where('institution_id', auth()->user()->institution->id)->get();
        } else {
            $teacher_objs = User::role('Teacher')->get();
        }

        foreach($teacher_objs as $obj) {
            $temp = [$obj->id => $obj->fullName()];
            $teachers += $temp;
        }

        $data = [];
        foreach($subjects as $subject) {
            $temp = [];
            $temp['index'] = '';
            $temp['subject'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                        <div class="avatar avatar-sm mr-8pt">
                            <span class="avatar-title rounded-circle bg-accent text-white">' . substr($subject->title, 0, 2) . '</span>
                        </div>
                        <div class="media-body">
                            <div class="d-flex flex-column">
                                <small class="js-lists-values-project"><strong>' . $subject->title . '</strong></small>
                            </div>
                        </div>
                    </div>';
            
            $temp['classes'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                        <div class="avatar avatar-sm mr-8pt">
                            <span class="avatar-title rounded bg-primary text-white">' . substr($subject->grade->name, 0, 2) . '</span>
                        </div>
                        <div class="media-body">
                            <div class="d-flex flex-column">
                                <small class="js-lists-values-project"><strong>' . $subject->grade->name . '</strong></small>
                            </div>
                        </div>
                    </div>';

            $temp['teachers'] = '';

            foreach($subject->teachers as $teacher) {

                $temp['teachers'] .= '<div class="avatar avatar-sm mr-8pt" data-toggle="tooltip" data-original-title="{{ $teacher->fullName() }}">
                                        <span class="avatar-title rounded-circle bg-warning">' . substr($teacher->fullName(), 0, 2) . '</span>
                                    </div>';
            }

            $temp['actions'] = '<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_curriculum_'. $subject->id .'">
                                    <i class="material-icons">edit</i>
                                </button>';

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /** Update */
    public function update(Request $request)
    {
        $subject = Course::find($request->subject_id);
        // Update Grade;
        $subject->class_id = $request->grade;
        $subject->save();

        // Add teachers to Subject
        foreach($request->teachers as $teacher) {
            DB::table('course_user')->updateOrInsert(
                [
                    'course_id' => $request->subject_id,
                    'user_id' => $teacher
                ],
                [
                    'course_id' => $request->subject_id,
                    'user_id' => $teacher
                ]
            );
        }

        return response([
            'success' => true
        ]);
    }
}
