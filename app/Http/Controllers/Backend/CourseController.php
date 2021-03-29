<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\Media;
use App\Models\Grade;
use App\Models\ChapterStudent;

use App\Services\ColorService;
use App\Services\CalendarService;

class CourseController extends Controller
{
    use FileUploadTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *  Show all of Courses
     */
    public function index() {

        $count = [
            'all' => Course::all()->count(),
            'draft' => Course::where('published', 0)->count(),
            'published' => Course::where('published', 1)->count(),
            'deleted' => Course::onlyTrashed()->count()
        ];

        return view('backend.courses.index', compact('count'));
    }

    /**
     * List data for Datatable
     */
    public function getList($type) {

        $count = [
            'all' => Course::all()->count(),
            'draft' => Course::where('published', 0)->count(),
            'published' => Course::where('published', 1)->count(),
            'deleted' => Course::onlyTrashed()->count()
        ];

        switch ($type) {
            case 'all':
                $courses = Course::all();
            break;
            case 'draft':
                $courses = Course::where('published', 0)->get();
            break;
            case 'published':
                $courses = Course::where('published', 1)->get();
            break;
            case 'deleted':
                $courses = Course::onlyTrashed()->get();
            break;
            default:
                $courses = Course::all();
        }

        $data = $this->getArrayData($courses);
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    /**
     * Create a Course.
     */ 
    public function create() {
    	$teachers = User::role('Teacher')->get();
    	$classes = Grade::all();
        return view('backend.courses.create', compact('teachers', 'classes'));
    }

    /**
     * Store new course data
     */
    public function store(Request $request) {

        $data = $request->all();

        // Course Data
        $course_data = [
        	'institution_id' => auth()->user()->institution->id,
        	'class_id' => $data['class'],
            'title' => $data['title'],
            'slug' => $this->get_slug($data['title']),
            'short_description' => $data['short_description'],
            'description' => $data['course_description'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'repeat' => $data['repeat'],
            'repeat_value' => $data['repeat_value'],
            'repeat_type' => $data['repeat_type'],
            'style' => rand(0, 10)
        ];

        if(isset($data['action']) && $data['action'] == 'publish') {
            $course_data['published'] = 1;  // Pending status - Sent to publish request
        }

        // Course image
        if(!empty($data['course_image'])) {
            $image = $request->file('course_image');
            $course_image_url = $this->saveImage($image, 'upload', true);
            $course_data['course_image'] = $course_image_url;
        }

        // Create Media
        if(!empty($data['course_video'])) {

            // Add Media
            $video_id = array_last(explode('/', $data['course_video']));

            $media_data = [
                'model_type' => 'App\Models\Course',
                'name' => $data['title'] . ' - Video',
                'url' => $data['course_video'],
                'type' => 'video',
                'file_name' => $video_id,
                'size' => 0
            ];
        }

        $message = '';
        $course_id = (!empty($data['course_id'])) ? $data['course_id'] : '';

        if(empty($course_id)) {
            try {
                $course = Course::create($course_data);
                $course_id = $course->id;

                // Add teacher to this course (me)
                DB::table('course_user')->insert([
                    'course_id' => $course_id,
                    'user_id' => $data['teacher']
                ]);

                if(!empty($media_data)) {
                    $media_data['model_id'] = $course_id;
                    $media = Media::create($media_data);
                }

            } catch(Exception $e) {
                $message .= $e->getMessage();
            }

        } else {

            try {
                $rlt = Course::find($course_id)->update($course_data);

                // Update Media
                $media = Media::where('model_type', 'App\Models\Course')
                    ->where('model_id', $course_id)->first();

                if(!empty($media_data)) {
                    if(empty($media)) {
                        $media_data['model_id'] = $course_id;
                        $media = Media::create($media_data);
                    } else {
                        $media->update($media_data);
                    }
                }
                
            } catch(Exception $e) {
                $message .= $e->getMessage();
            }
        }

        if(empty($message)) {
            return response()->json([
                'success' => true,
                'course_id' => $course_id
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }

    private function get_slug($title) {
        $slug = str_slug($title);
    
        if ($this->slugExist($slug)) {
        	$title = $title . '_1';
            return $this->get_slug($title);
        }
    
        // otherwise, it's valid and can be used
        return $slug;
    }
    
    private function slugExist($slug) {
        return empty(Course::where('slug', $slug)->first()) ? false : true;
    }

    /**
     * Edit course
     */
    public function edit($id, CalendarService $calendarService)
    {
        if(auth()->user()->hasRole('Administrator')) {
            $teachers = User::role('Teacher')->get();
        } else {
            $teachers = User::role('Teacher')->where('institution_id', auth()->user()->institution->id)->get();
        }
    	
    	$classes = Grade::all();
        $course = Course::find($id);
        $schedules = $calendarService->getOnePeriodSchedule($id);
        
        return view('backend.courses.edit', compact('course', 'teachers', 'classes', 'schedules'));
    }

    /**
     * Update Course
     */
    public function update(Request $request, $id, ColorService $colorService) {

        $course = Course::find($id);

        $data = $request->all();

        // Course Data
        $course_data = [
        	'institution_id' => auth()->user()->institution->id,
        	'class_id' => $data['class'],
            'title' => $data['title'],
            'slug' => $this->get_slug($data['title']),
            'short_description' => $data['short_description'],
            'description' => $data['course_description'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'repeat' => $data['repeat'],
            'repeat_value' => $data['repeat_value'],
            'repeat_type' => $data['repeat_type'],
            'style' => rand(0, 10)
        ];

        if(isset($data['action']) && $data['action'] == 'pending') {
            $course_data['published'] = 1;  // Pending status - Sent to publish request
        }

        if(isset($data['action']) && $data['action'] == 'draft') {
            $course_data['published'] = 0;  // Pending status - Sent to publish request
        }

        // Course image
        if(!empty($data['course_image'])) {
            $image = $request->file('course_image');

            // Delete existing img file
            if (File::exists(public_path('/storage/uploads/' . $course->course_image))) {
                File::delete(public_path('/storage/uploads/' . $course->course_image));
                File::delete(public_path('/storage/uploads/thumb/' . $course->course_image));
            }

            $course_image_url = $this->saveImage($image, 'upload', true);
            $course_data['course_image'] = $course_image_url;
        }

        try {
            $course->update($course_data);
        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
 
        // Update Course Media - Course video
        if(!empty($data['course_video'])) {
            $video_id = array_last(explode('/', $data['course_video']));
            $media_data = [
                'model_type' => 'App\Models\Course',
                'name' => $data['title'] . ' - Video',
                'url' => $data['course_video'],
                'type' => 'video',
                'file_name' => $video_id,
                'size' => 0
            ];

            $media = Media::where('model_type', 'App\Models\Course')
                ->where('model_id', $id)->first();

            if(empty($media)) {
                $media_data['model_id'] = $id;
                $media = Media::create($media_data);
            } else {
    
                try {
                    Media::where('model_type', 'App\Models\Course')
                        ->where('model_id', $id)->update($media_data);
                } catch (Exception $e) {
                    $error = $e->getMessage();
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Delete a Course
     */
    public function destroy($id) {

        try {
            Course::find($id)->delete();

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

    /**
     * Restore a Course
     */
    public function restore($id) {

        try {
            Course::withTrashed()->find($id)->restore();

            return response()->json([
                'success' => true,
                'action' => 'restore'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Complete course
     */
    public function complete(Request $request) {
        $course = Course::find($request->course_id);
        $update_data = [
            'model_type' => Course::class,
            'model_id' => $request->course_id,
            'user_id' => auth()->user()->id,
            'course_id' => $request->course_id
        ];

        try {
            ChapterStudent::updateOrCreate($update_data, $update_data);
            return redirect()->route('courses.show', $course->slug);
        } catch (Exception $e) {

            return back()->withErrors([$e->getMessage()]);
        }

    }

    /**
     * Publish or Unpublish
     */
    public function publish($id)
    {
        $course = Course::find($id);
        if($course->published == 1) {
            $course->published = 2;
        } else {
            $course->published = 1;
        }

        $course->save();

        return response()->json([
            'success' => true,
            'action' => 'publish',
            'published' => $course->published
        ]);
    }

    /**
     * Delete Forever
     */
    public function foreverDelete($id)
    {
        try {

            // Delete from course_user table;
            DB::table('course_user')->where('course_id', $id)->delete();
            Course::withTrashed()->where('id', $id)->forceDelete();

            // Delete lessons
            $lesson_ids = Lesson::where('course_id', $id)->pluck('id');
            Step::whereIn('lesson_id', $lesson_ids)->delete();
            Lesson::where('course_id', $id)->forceDelete();

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

    public function getArrayData($courses) {
        $data = [];
        $i = 0;

        foreach($courses as $course) {

            $i++;
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i;

            if($course->grade) {
                $grade_name = $course->grade->name;
            } else {
                $grade_name = 'N/A';
            }

            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'
                                        . substr($course->title, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $course->title . '</strong></small>
                                        <small class="js-lists-values-location text-50">'. $grade_name .'</small>
                                    </div>
                                </div>
                            </div>';

            $temp['owner'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded-circle">' . substr($course->institution->name, 0, 2) . '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'
                                            . $course->institution->name . '</strong></p>
                                            <small class="js-lists-values-email text-50">'. $course->teachers[0]->name . '</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';

            if($course->published == 1) {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Published</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>';
            } else if($course->published == 0) {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Unpublished</small>
                                    <span class="indicator-line rounded bg-warning"></span>
                                </div>';
            } else {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Pending</small>
                                    <span class="indicator-line rounded bg-info"></span>
                                </div>';
            }

            if($course->end_date < Carbon::now()->format('Y-m-d')) {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Out date</small>
                                    <span class="indicator-line rounded bg-danger"></span>
                                </div>';
            }

            $show_route = route('courses.show', $course->slug);
            $edit_route = route('admin.courses.edit', $course->id);
            $delete_route = route('admin.courses.destroy', $course->id);
            $publish_route = route('admin.courses.publish', $course->id);

            $btn_show = view('layouts.buttons.show', ['show_route' => $show_route])->render();
            $btn_edit = view('layouts.buttons.edit', ['edit_route' => $edit_route])->render();
            $btn_delete = view('layouts.buttons.delete', ['delete_route' => $delete_route])->render();

            if($course->published == 2) {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-success btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="Publish"><i class="material-icons">arrow_upward</i></a>';
            } else if($course->published == 1) {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-info btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="UnPublish"><i class="material-icons">arrow_downward</i></a>';
            } else {
                $btn_publish = '';
            }

            if(auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Institution Admin')) {
                $temp['action'] = $btn_show . '&nbsp;' . $btn_publish . '&nbsp;';
            } else {
                $temp['action'] = $btn_show . '&nbsp;';
            }
            
            if(auth()->user()->hasPermissionTo('course_edit')) {
                $temp['action'] .= $btn_edit . '&nbsp;';
            }

            if(auth()->user()->hasPermissionTo('course_delete')) {
                $temp['action'] .= $btn_delete . '&nbsp;';
            }

            if($course->trashed()) {
                $restore_route = route('admin.courses.restore', $course->id);
                $forever_delete_route = route('admin.courses.foreverDelete', $course->id);

                $btn_restore = '<a href="'. $restore_route. '" class="btn btn-info btn-sm" data-action="restore" data-toggle="tooltip"
                data-original-title="Restore to Review"><i class="material-icons">arrow_back</i></a>';

                $perment_delete = '<a href="'. $forever_delete_route. '" class="btn btn-accent btn-sm" data-action="restore" data-toggle="tooltip"
                data-original-title="Delete Forever"><i class="material-icons">delete_forever</i></a>';

                $temp['action'] = $btn_restore . '&nbsp;' . $perment_delete;
            }

            array_push($data, $temp);
        }

        return $data;
    }

    public function studentCourses()
    {
        return view('backend.courses.student');
    }

    public function getStudentCoursesByAjax()
    {
        $courses = Course::all();
        $data = $this->getStudentData($courses);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getStudentData($courses) {
        $data = [];
        $i = 0;

        foreach($courses as $course) {
            $i++;
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i;
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'
                                        . substr($course->title, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $course->title . '</strong></small>
                                        <small class="js-lists-values-location text-50">'. $course->slug .'</small>
                                    </div>
                                </div>
                            </div>';
            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded-circle">' . substr($course->teachers[0]->name, 0, 2) . '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'
                                            . $course->teachers[0]->name . '</strong></p>
                                            <small class="js-lists-values-email text-50">Teacher</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';

            $temp['progress'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">'. $course->progress() . '% </small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>';

            $show_route = route('courses.show', $course->slug);
            $btn_show = view('layouts.buttons.show', ['show_route' => $show_route]);
            $temp['action'] = $btn_show . '&nbsp;';

            array_push($data, $temp);
        }

        return $data;
    }

    /**
     * Add Favorite
     */
    public function addFavorite($course_id)
    {
        $rlt = DB::table('course_favorite')->insert([
            'course_id' => $course_id,
            'user_id' => auth()->user()->id
        ]);

        if($rlt) {
            return response()->json([
                'success' => true,
                'action' => 'add_favorite'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'action' => 'add_favorite'
            ]);
        }
        
    }

    /**
     * Remove course from Favoirtes
     */
    public function removeFavorite($course_id)
    {
        $favorite = DB::table('course_favorite')->where('course_id', $course_id)->where('user_id', auth()->user()->id);
        if($favorite->count() > 0) {
            $rlt = $favorite->delete();
            if($rlt) {
                return response()->json([
                    'success' => true,
                    'action' => 'remove_favorite'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'action' => 'remove_favorite'
                ]);
            }
        }
    }

    /**
     * Get favorite Courses
     */
    public function favorites()
    {
        $favorites = DB::table('course_favorite')->where('user_id', auth()->user()->id)->pluck('course_id');
        $courses = Course::whereIn('id', $favorites)->paginate(10);
        return view('backend.courses.favorites', compact('courses'));
    }
}
