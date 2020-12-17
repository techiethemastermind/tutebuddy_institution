<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

use App\User;
use Spatie\Permission\Models\Role;
use Hash;
use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Str;
use App\Models\Institution;

class UserController extends Controller
{
    use FileUploadTrait;

    /**
     * Constructor of the resource.
     */
    function __construct()
    {
        // Define Permission
        $this->middleware('permission:user_access', ['only' => ['index', 'show']]);
        $this->middleware('permission:user_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $institutions = Institution::all();
        $users = User::orderBy('created_at','desc')->paginate(10);
        $action = 'users';
        return view('backend.users.index',compact('users', 'institutions', 'action'));
    }

    /**
     * Display Institution Admins
     * 
     * @return \Illuminate\Http\Response
     */
    public function admins()
    {
        return view('backend.users.admins');
    }

    /**
     * Display Teachers
     * 
     * @return \Illuminate\Http\Response
     */
    public function teachers()
    {
        return view('backend.users.teachers');
    }

    /**
     * Display Students
     * 
     * @return \Illuminate\Http\Response
     */
    public function students()
    {
        return view('backend.users.students');
    }

    /**
     * Display Institution Admins
     */
    public function getAdminsByAjax()
    {
        $inst_users = User::role('Institution Admin')->get();
        $admin_users = User::role('Admin')->get();
        $users = collect();

        foreach($inst_users as $user) {
            $users->add($user);
        }

        foreach($admin_users as $user) {
            $users->add($user);
        }

        $data = [];
        $i = 1;
        foreach($users as $user) {
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i++;

            if(empty($user->avatar)) {
                $avatar = '<span class="avatar-title rounded-circle">'. substr($user->name, 0, 2) .'</span>';
            } else {
                $avatar = '<img src="'. asset('/storage/avatars/' . $user->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">';
            }

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">'. $avatar .'</div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'. $user->name .'</strong></p>
                                            <small class="js-lists-values-email text-50">'. $user->institution->name .'</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            
            $temp['email'] = '<strong>' . $user->email . '</strong>';

            if($user->status) {
                $temp['status'] = '<div class="d-flex flex-column">
                                <small class="js-lists-values-status text-50 mb-4pt">Active</small>
                                <span class="indicator-line rounded bg-success"></span>
                            </div>';
            } else {
                $temp['status'] = '<div class="d-flex flex-column">
                                <small class="js-lists-values-status text-50 mb-4pt">Inactive</small>
                                <span class="indicator-line rounded bg-accent"></span>
                            </div>';
            }

            $show_route = route('admin.users.show', $user->id);
            $btn_show = view('layouts.buttons.show', ['show_route' => $show_route])->render();

            $edit_route = route('admin.users.edit', $user->id);
            $btn_edit = view('layouts.buttons.edit', ['edit_route' => $edit_route])->render();

            $delete_route = route('admin.users.destroy', $user->id);
            $btn_delete = view('layouts.buttons.delete', ['delete_route' => $delete_route])->render();

            if($user->hasRole('Admin')) {
                $temp['actions'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_delete;
            } else {
                $temp['actions'] = $btn_show . '&nbsp;' . $btn_edit;;
            }

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getTeachersByAjax()
    {
        $users = User::role('Teacher')->get();
        $data = [];
        $i = 1;
        foreach($users as $user) {
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i++;

            if(empty($user->avatar)) {
                $avatar = '<span class="avatar-title rounded-circle">'. substr($user->name, 0, 2) .'</span>';
            } else {
                $avatar = '<img src="'. asset('/storage/avatars/' . $user->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">';
            }

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">'. $avatar .'</div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'. $user->name .'</strong></p>
                                            <small class="js-lists-values-email text-50">'. $user->headline .'</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            
            $temp['email'] = '<strong>' . $user->email . '</strong>';
            $temp['subjects'] = '<strong>' .$user->courses->count() . '</strong>';

            if($user->status) {
                $temp['status'] = '<div class="d-flex flex-column">
                                <small class="js-lists-values-status text-50 mb-4pt">Active</small>
                                <span class="indicator-line rounded bg-success"></span>
                            </div>';
            } else {
                $temp['status'] = '<div class="d-flex flex-column">
                                <small class="js-lists-values-status text-50 mb-4pt">Inactive</small>
                                <span class="indicator-line rounded bg-accent"></span>
                            </div>';
            }

            $show_route = route('admin.users.show', $user->id);
            $btn_show = view('layouts.buttons.show', ['show_route' => $show_route])->render();

            $edit_route = route('admin.users.edit', $user->id);
            $btn_edit = view('layouts.buttons.edit', ['edit_route' => $edit_route])->render();

            $delete_route = route('admin.users.destroy', $user->id);
            $btn_delete = view('layouts.buttons.delete', ['delete_route' => $delete_route])->render();

            $temp['actions'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_delete;

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getStudentsByAjax(Request $request)
    {
        $users = User::role('Student')->get();
        $data = [];
        $i = 1;
        foreach($users as $user) {
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i++;

            if(empty($user->avatar)) {
                $avatar = '<span class="avatar-title rounded-circle">'. substr($user->name, 0, 2) .'</span>';
            } else {
                $avatar = '<img src="'. asset('/storage/avatars/' . $user->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">';
            }

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">'. $avatar .'</div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'. $user->name .'</strong></p>
                                            <small class="js-lists-values-email text-50">'. $user->institution->name .'</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            
            $temp['email'] = '<strong>' . $user->email . '</strong>';
            $temp['class'] = '<strong>' . $user->grade[0]->name . '</strong>';

            if($user->status) {
                $temp['status'] = '<div class="d-flex flex-column">
                                <small class="js-lists-values-status text-50 mb-4pt">Active</small>
                                <span class="indicator-line rounded bg-success"></span>
                            </div>';
            } else {
                $temp['status'] = '<div class="d-flex flex-column">
                                <small class="js-lists-values-status text-50 mb-4pt">Inactive</small>
                                <span class="indicator-line rounded bg-accent"></span>
                            </div>';
            }

            $show_route = route('admin.users.show', $user->id);
            $btn_show = view('layouts.buttons.show', ['show_route' => $show_route])->render();

            $edit_route = route('admin.users.edit', $user->id);
            $btn_edit = view('layouts.buttons.edit', ['edit_route' => $edit_route])->render();

            $delete_route = route('admin.users.destroy', $user->id);
            $btn_delete = view('layouts.buttons.delete', ['delete_route' => $delete_route])->render();

            $temp['actions'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_delete;

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('backend.users.create', compact('roles'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        if(in_array('Admin', $input['roles'])) { // Admin limited with 2
            if(!User::role('Admin')->count() < 3) {
                return back()->with('error', 'Admin user is limited');
            }
        }
    
        $user = User::create($input);

        $avatar = $request->has('avatar') ? $request->file('avatar') : false;
        if($avatar) {
            $avatar_url = $this->saveImage($avatar, 'avatar');
            $user->avatar = $avatar_url;
            $user->save();
        }
        
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('admin.users.edit', $user->id)
                        ->with('success','User created successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('backend.users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('backend.users.edit',compact('user', 'roles', 'userRole'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {    
        $input = $request->all();

        if(!empty($input['password'])) { 
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = array_except($input, array('password'));    
        }

        $user = User::find($id);
        $user->update($input);

        $avatar = $request->has('avatar') ? $request->file('avatar') : false;
        if($avatar) {
            $avatar_url = $this->saveImage($avatar, 'avatar');
            $user->avatar = $avatar_url;
            $user->save();
        }

        DB::table('model_has_roles')->where('model_id', $id)->delete();
    
        $user->assignRole($request->input('roles'));
    
        return back()->with('success','User updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            User::find($id)->delete();

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

    public function myAccount()
    {
        $user = auth()->user();
        return view('backend.users.account', compact('user'));
    }

    public function updateAccount(Request $request, $id)
    {
        $input = $request->all();

        $user = User::find($id);

        if(isset($input['update_type']) && $input['update_type'] == 'password') {

            $hashedPassword = $user->password;

            if (Hash::check($input['current_password'], $hashedPassword)) {
                if($input['new_password'] == $input['confirm_password']) {
                    $input['password'] = Hash::make($input['new_password']);
                } else {
                    return response()->json([
                        'success' => false,
                        'action' => 'update',
                        'message' => 'Please confirm password'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'action' => 'update',
                    'message' => 'Incorrect current password provided'
                ]);
            }
        }
        
        $user->update($input);

        $avatar = $request->has('avatar') ? $request->file('avatar') : false;
        if($avatar) {
            $avatar_url = $this->saveImage($avatar, 'avatar');
            $user->avatar = $avatar_url;
            $user->save();
        }

        if(isset($input['categories'])) {
            $user->profession = json_encode($input['categories']);
            $user->save();
        }

        if(isset($input['qualification'])) {
            $user->qualifications = json_encode($input['qualification']);
            $user->save();
        }

        if(isset($input['achievement'])) {
            $user->achievements = json_encode($input['achievement']);
            $user->save();
        }

        return response()->json([
            'success' => true,
            'action' => 'update'
        ]);
    }

    public function search(Request $request)
    {
        $params = $request->all();
    }

    public function studentInstructors()
    {
        return view('backend.users.my-teachers');
    }

    public function getStudentInstructorsByAjax()
    {
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $teacher_ids = DB::table('course_user')->whereIn('course_id', $course_ids)->pluck('user_id');
        $teachers = User::whereIn('id', $teacher_ids)->get();

        $data = [];
        foreach($teachers as $teacher) {
            $temp = [];
            $temp['index'] = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                        <label class="custom-control-label"><span class="text-hide">Check</span></label>
                    </div>';

            if(empty($teacher->avatar)) {
                $avatar = '<span class="avatar-title rounded-circle">'. substr($teacher->name, 0, 2) .'</span>';
            } else {
                $avatar = '<img src="'. asset('/storage/avatars/' . $teacher->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">';
            }

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">'. $avatar .'</div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'. $teacher->name .'</strong></p>
                                            <small class="js-lists-values-email text-50">'. $teacher->headline .'</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';

            $temp['email'] = '<strong>' . $teacher->email . '</strong>';

            $btn_follow = '<a href="#" target="_blank" class="btn btn-primary btn-sm">Follow</a>';
            $btn_show = '<a href="'. route('profile.show', $teacher->uuid) .'" class="btn btn-accent btn-sm">View Profile</a>';

            $temp['action'] = $btn_follow . '&nbsp;' . $btn_show;

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function enrolledStudents()
    {
        return view('backend.users.teacher');
    }

    public function getEnrolledStudentsByAjax()
    {
        $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
        $student_ids = DB::table('course_student')->whereIn('course_id', $course_ids)->pluck('user_id');
        $students = User::whereIn('id', $student_ids)->get();

        $data = [];
        foreach($students as $student) {
            $temp = [];
            $temp['index'] = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                        <label class="custom-control-label"><span class="text-hide">Check</span></label>
                    </div>';
            
            if(!empty($student->avatar)) {
                $avatar = '<img src="'. asset('/storage/avatars/' . $student->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">';
            } else {
                $avatar = '<span class="avatar-title rounded-circle">'. substr($student->name, 0, 2) .'</span>';
            }

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    '. $avatar .'
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-name">'. $student->name .'</strong></p>
                                            <small class="js-lists-values-email text-50">'. $student->email .'</small>
                                        </div>
                                        <div class="d-flex align-items-center ml-24pt">
                                            <i class="material-icons text-20 icon-16pt">comment</i>
                                            <small class="ml-4pt"><strong class="text-50">1</strong></small>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            $temp['course'] = '<strong>'. $student->studentCourse()->title .'</strong>';
            $temp['start_date'] = '<strong>'. $student->studentCourse()->start_date .'</strong>';
            $temp['end_date'] = '<strong>'. $student->studentCourse()->end_date .'</strong>';

            if($student->studentCourse()->progress() > 99) {
                $status = '<span class="indicator-line rounded bg-success"></span>';
            } else {
                $status = '<span class="indicator-line rounded bg-primary"></span>';
            }
            $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">'. $student->studentCourse()->progress() .'%</small>
                                    '. $status .'
                                </div>';

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
