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
use App\Models\Grade;
use App\Models\Division;
use App\Models\Course;

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
        $users = User::where('institution_id', '!=', 0)->orderBy('created_at','desc')->paginate(10);
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
        if(auth()->user()->hasRole('Administrator')) {
            $inst_users = User::role('Institution Admin')->get();
            $admin_users = User::role('Admin')->get();
        } else {
            $inst_users = User::role('Institution Admin')->where('institution_id', '=', auth()->user()->institution_id)->get();
            $admin_users = User::role('Admin')->where('institution_id', '=', auth()->user()->institution_id)->get();
        }
        
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
                $avatar = '<span class="avatar-title rounded-circle">'. substr($user->fullName(), 0, 2) .'</span>';
            } else {
                $avatar = '<img src="'. asset('/storage/avatars/' . $user->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">';
            }

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">'. $avatar .'</div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'. $user->fullName() .'</strong></p>
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
        if(auth()->user()->institution) {
            $users = User::role('Teacher')->where('institution_id', auth()->user()->institution->id)->get();
        } else {
            $users = User::role('Teacher')->get();
        }
        
        $data = [];
        $i = 1;
        foreach($users as $user) {
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i++;

            if(empty($user->avatar)) {
                $avatar = '<span class="avatar-title rounded-circle">'. substr($user->fullName(), 0, 2) .'</span>';
            } else {
                $avatar = '<img src="'. asset('/storage/avatars/' . $user->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">';
            }

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">'. $avatar .'</div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'. $user->fullName() .'</strong></p>
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

            if($user->courses->count() > 0) {
                $temp['actions'] = $btn_show . '&nbsp;' . $btn_edit;
            } else {
                $temp['actions'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_delete;
            }

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getStudentsByAjax(Request $request)
    {
        if(auth()->user()->institution) {

            if(auth()->user()->hasRole('Teacher')) {

                $courses = Course::all();
                
                $users = collect();
                foreach($courses as $course) {
                    $course_grade = $course->grade;
                    $course_grade_students = $course_grade->students;
    
                    foreach($course_grade_students as $item) {    
                        $users->push($item);
                    }
                }
            } else {
                $users = User::role('Student')->where('institution_id', auth()->user()->institution_id)->get();
            }

        } else {
            $users = User::role('Student')->get();
        }

        $data = [];
        $i = 1;
        foreach($users as $user) {
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i++;

            if(empty($user->avatar)) {
                $avatar = '<span class="avatar-title rounded-circle">'. substr($user->fullName(), 0, 2) .'</span>';
            } else {
                $avatar = '<img src="'. asset('/storage/avatars/' . $user->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">';
            }

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">'. $avatar .'</div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'. $user->fullName() .'</strong></p>
                                            <small class="js-lists-values-email text-50">'. $user->institution->name .'</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            
            $temp['email'] = '<strong>' . $user->email . '</strong>';
            
            if($user->grade->count() > 0) {
                $temp['class'] = '<strong>' . $user->grade->first()->name  . '</strong>';
            } else {
                $temp['class'] = '<strong>N/A</strong>';
            }

            if($user->division->count() > 0) {
                $temp['division'] = '<strong>' . $user->division->first()->name . '</strong>';
            } else {
                $temp['division'] = '<strong>N/A</strong>';
            }            

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

            if(auth()->user()->hasRole('Teacher')) {
                $temp['actions'] = $btn_show . '&nbsp;';
            } else {
                $temp['actions'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_delete;
            }

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
        $institutions = Institution::all();
        return view('backend.users.create', compact('roles', 'institutions'));
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

        if(!isset($input['roles'])) {
            if(isset($input['fixed_role'])) {
                $input['roles'] = [$input['fixed_role']];
            } else {
                return back()->with('error', 'New User role is not selected');
            }
        }

        if(in_array('Admin', $input['roles'])) { // Admin limited with 2
            if(!User::role('Admin')->count() < 3) {
                return back()->with('error', 'Admin user is limited');
            }
        }

        $input['uuid'] = Str::uuid()->toString();
        $input['user_name'] = $input['first_name'] . '_' . uniqid();

        if(auth()->user()->institution) {
            $input['institution_id'] = auth()->user()->institution->id;
        } else {
            $input['institution_id'] = $input['institution'];
        }
    
        $user = User::create($input);
        $avatar = $request->has('avatar') ? $request->file('avatar') : false;
        if($avatar) {
            $avatar_url = $this->saveImage($avatar, 'avatar');
            $user->avatar = $avatar_url;
            $user->save();
        }

        $user->assignRole($input['roles']);
        if(isset($input['grade'])) {
            $grade_user = DB::table('class_user')->updateOrInsert(
                [
                    'grade_id' => $input['grade'],
                    'user_id' => $user->id
                ],
                [
                    'grade_id' => $input['grade'],
                    'user_id' => $user->id
                ]
            );
        }
        if(isset($input['division'])) {
            $division_user = DB::table('division_user')->updateOrInsert(
                [
                    'division_id' => $input['division'],
                    'user_id' => $user->id
                ],
                [
                    'division_id' => $input['division'],
                    'user_id' => $user->id
                ]
            );
        }

        if($input['fixed_role'] == 'Student') {
            // Create Parent
            DB::table('parent')->insert([
                'user_id' => $user->id,
                'father_name' => $input['father_name'],
                'father_mobile' => $input['father_mobile'],
                'father_email' => $input['father_email'],
                'mother_name' => $input['mother_name'],
                'mother_mobile' => $input['mother_mobile'],
                'mother_email' => $input['mother_email'],
                'contact_email' => $input['communication_email'],
                'contact_phone' => $input['communication_phone']
            ]);
        }

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
        $grades = Grade::pluck('name', 'id')->all();

        $institutions = Institution::all();

        if($user->grade->count() > 0) {
            $divisions = $user->grade[0]->divisions->pluck('name', 'id');
        } else {
            $divisions = Division::pluck('name', 'id')->all();
        }

        // For student
        if($user->hasRole('Student')) {
            $parent = DB::table('parent')->where('user_id', $id)->first();
            return view('backend.users.edit', compact('user', 'roles', 'userRole', 'grades', 'divisions', 'institutions', 'parent'));
        } else {
            return view('backend.users.edit', compact('user', 'roles', 'userRole', 'grades', 'divisions', 'institutions'));
        }
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

        if(isset($input['grade'])) {
            DB::table('class_user')->updateOrInsert(
                ['user_id' => $id],
                ['grade_id' => $input['grade']]
            );
        }

        if(isset($input['division'])) {
            DB::table('division_user')->updateOrInsert(
                ['user_id' => $id],
                ['division_id' => $input['division']]
            );
        }

        if(isset($input['roles'])) {
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($request->input('roles'));
        }

        if($user->hasRole('Student')) {
            // Create Parent
            DB::table('parent')->update([
                'user_id' => $user->id,
                'father_name' => $input['father_name'],
                'father_mobile' => $input['father_mobile'],
                'father_email' => $input['father_email'],
                'mother_name' => $input['mother_name'],
                'mother_mobile' => $input['mother_mobile'],
                'mother_email' => $input['mother_email'],
                'contact_email' => $input['contact_email'],
                'contact_phone' => $input['contact_phone']
            ]);
        }
    
        return back()->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        
        if($user->courses->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'This user is assigned to subjects'
            ]);
        }

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

    /**
     * Upload Users by CSV file
     */
    public function importCSV(Request $request, $type)
    {
        if(!$request->file('csv_file')) {
            return response()->json([
                'success' => false,
                'message' => 'File not attached'
            ]);
        }
        
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $csv_data = array_slice($data, 0);
        $my_institution = auth()->user()->institution;
        $grades = $my_institution->classes->pluck('id', 'name');
        $divisions = $my_institution->divisions->pluck('id', 'name');

        $start = false;
        $_emails = [];

        // Check email is duplicated or not
        foreach($csv_data as $data) {
            $user_count = User::where('email', $data[4])->count();
            if($user_count > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email address already exist. duplicated email address is ' . $data[4]
                ]);
            }

            if(!in_array($data[4], $_emails)) {
                array_push($_emails, $data[4]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Email address duplicated. duplicated email address is ' . $data[4]
                ]);
            }
        }

        foreach($csv_data as $data) {
            if($start) {
                if($type == 'teacher') {
                    $user_data = [
                        'uuid' => Str::uuid()->toString(),
                        'institution_id' => $my_institution->id,
                        'user_name' => strtolower($data[1]) . '_' . uniqid(),
                        'headline' => $data[0],
                        'first_name' => $data[1],
                        'middle_name' => $data[2],
                        'last_name' => $data[3],
                        'email' => $data[4],
                        'phone_number' => $data[5],
                        'timezone' => $my_institution->timezone
                    ];

                    $user_data['password'] = Hash::make('secret');
                    $user = User::create($user_data);
                    $user->assignRole(['Teacher']);
                }

                if($type == 'student') {
                    
                    $user_data = [
                        'uuid' => Str::uuid()->toString(),
                        'institution_id' => $my_institution->id,
                        'user_name' => strtolower($data[1]) . '_' . uniqid(),
                        'roll_no' => $data[1],
                        'first_name' => $data[2],
                        'middle_name' => $data[3],
                        'last_name' => $data[4],
                        'email' => $data[7],
                        'birthday' => $data[8],
                        'timezone' => $my_institution->timezone
                    ];
                    
                    $user_data['password'] = Hash::make('secret');

                    $user = User::create($user_data);
                    $user->assignRole(['Student']);

                    $grade_user = DB::table('class_user')->updateOrInsert(
                        [
                            'grade_id' => $grades[$data[5]],
                            'user_id' => $user->id
                        ],
                        [
                            'grade_id' => $grades[$data[5]],
                            'user_id' => $user->id
                        ]
                    );

                    $division_user = DB::table('division_user')->updateOrInsert(
                        [
                            'division_id' => $divisions[$data[6]],
                            'user_id' => $user->id
                        ],
                        [
                            'division_id' => $divisions[$data[6]],
                            'user_id' => $user->id
                        ]
                    );

                    // Create Parent
                    DB::table('parent')->insert([
                        'user_id' => $user->id,
                        'father_name' => $data[9],
                        'father_mobile' => $data[10],
                        'father_email' => $data[11],
                        'mother_name' => $data[12],
                        'mother_mobile' => $data[13],
                        'mother_email' => $data[14],
                        'contact_email' => $data[15],
                        'contact_phone' => $data[16]
                    ]);
                }

            } else {
                $start = true;
            }
        }

        return response()->json([
            'success' => true
        ]);
    }
}
