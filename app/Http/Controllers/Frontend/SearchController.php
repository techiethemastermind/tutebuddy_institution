<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\User;

use Carbon\Carbon;

class SearchController extends Controller
{
    // Search Course
    public function courses(Request $request)
    {
        $params = $request->all();

        if(isset($params['_q'])) {
            $courses_me = Course::where('title', 'like', '%' . $params['_q'] . '%')->where('published', 1)->where('end_date', '>', Carbon::now()->format('Y-m-d'));
            $categories = Category::where('name', 'like', '%' . $params['_q'] . '%')->get();
            foreach($categories as $category) {
                $subCategories = Category::where('parent', $category->id)->get();
                foreach($subCategories as $subcategory) {
                    $courses_c = Course::where('category_id', $subcategory->id)->where('end_date', '>', Carbon::now()->format('Y-m-d'));
                    $courses_me = $courses_me->union($courses_c);
                }
            }
            $courses = $courses_me->paginate(10);
            $courses->setPath('search/courses?_q='. $params['_q']);
        } else {
            $courses = Course::where('published', 1)->where('end_date', '>', Carbon::now()->format('Y-m-d'))->paginate('10');
        }
        
        return view('frontend.search.courses', compact('courses'));
    }

    // Search instructor
    public function teachers(Request $request)
    {
        $params = $request->all();

        if(isset($params['_q'])) {
            $teachers = User::role('Instructor')
                ->where('name', 'like', '%' . $params['_q'] . '%')
                ->orWhere('headline', 'like', '%' . $params['_q'] . '%')->paginate(10);
        } else {
            $teachers = User::role('Instructor')->orderBy('created_at', 'desc')->paginate(10);
        }
        
        return view('frontend.search.teachers', compact('teachers'));
    }

    public function getSearchFormCourseData($key)
    {

        $data = [];
        $categories = Category::where('name', 'like', '%' . $key . '%')->get();

        foreach($categories as $category) {
            array_push($data, [
                'id' => $category->id,
                'name' => $category->name,
                'type' => 'category'
                ]
            );
        }

        $courses = Course::where('title', 'like', '%' . $key . '%')->get();

        foreach($courses as $course) {
            array_push($data, [
                'id' => $course->id,
                'name' => $course->title,
                'type' => 'course'
                ]
            );
        }

        $ele = '<ul id="search___result" class="list-unstyled search_result collapse show">';

        $i = 0;

        foreach($data as $item) {
            $i++;
            $ele .= '<li data-id="'. $item['id'] .'" data-type="'. $item['type'] .'">'. $item['name'] .'</li>';
            if($i > 5) {
                break;
            }
        }

        $ele .= '</ul>';

        return response()->json([
            'success' => true,
            'result' => $data,
            'html' => $ele
        ]);
    }

    // Search for Course by Semantic
    public function searchCourse(Request $request)
    {
        $q = $request->q;
        $data = [];
        $results = [];

        $courses = Course::where('title', 'like', '%' . $q . '%')->get();
        $search = [];

        foreach($courses as $course) {
            $image = ($course->course_image) ? asset('/storage/uploads/thumb/' . $course->course_image) : asset('/storage/uploads/no-image.jpg');
            $cat_id = isset($course->category) ? $course->category->id : '';
            array_push($search, [
                    'title' => $course->title,
                    'description' => $course->short_description,
                    'image' => $image,
                    'url' => config("app.url") . 'search/courses?_q=' . $course->title . '&_t=course&_k=' . $cat_id
                ]
            );
        }

        $results['course'] = [
            'name' => 'Course',
            'results' => $search
        ];

        $categories = Category::where('name', 'like', '%' . $q . '%')->get();
        $search = [];

        foreach($categories as $category) {
            $image = ($category->thumb) ? asset('/storage/uploads/' . $category->thumb) : asset('/storage/uploads/no-image.jpg');
            array_push($search, [
                    'title' => $category->name,
                    'description' => $category->description,
                    'image' => $image,
                    'url' => config("app.url") . 'search/courses?_q=' . $q . '&_t=category&_k=' . $category->id
                ]
            );
        }

        $results['category'] = [
            'name' => 'Category',
            'results' => $search
        ];

        $data['results'] = $results;
        return response()->json($data);
    }


    // Search for Instructor by Semantic
    public function searchInstructor(Request $request)
    {
        $q = $request->q;
        $data = [];
        $results = [];

        $users = User::role('Instructor')->where('name', 'like', '%' . $q . '%')->get();
        $instructors = [];

        foreach($users as $user) {
            $avatar = ($user->avatar) ? asset('/storage/avatars/'. $user->avatar) : asset('/storage/avatars/no-avatar.jpg');
            array_push($instructors, [
                    'title' => $user->name,
                    'description' => $user->headline,
                    'image' => $avatar,
                    'url' => config("app.url") . 'search/instructors?_q=' . $user->name . '&_t=user&_k=' . $user->id
                ]
            );
        }

        $results['name'] = [
            'name' => 'Match with Name',
            'results' => $instructors
        ];

        $subjects = User::role('Instructor')->where('headline', 'like', '%' . $q . '%')->get();
        $instructors = [];

        foreach($subjects as $user) {
            $avatar = ($user->avatar) ? asset('/storage/avatars/'. $user->avatar) : asset('/storage/avatars/no-avatar.jpg');
            array_push($instructors, [
                    'title' => $user->name,
                    'description' => $user->headline,
                    'image' => $avatar,
                    'url' => config("app.url") . 'search/instructors?_q=' . $q
                ]
            );
        }

        $results['headline'] = [
            'name' => 'Match with Headline',
            'results' => $instructors
        ];

        $courses = Course::where('title', 'like', '%' . $q . '%')->get();
        $instructors = [];

        foreach($courses as $course) {
            $teachers = $course->teachers;
            foreach($teachers as $user) {
                $avatar = ($user->avatar) ? asset('/storage/avatars/'. $user->avatar) : asset('/storage/avatars/no-avatar.jpg');
                array_push($instructors, [
                        'title' => $user->name,
                        'description' => $user->headline,
                        'image' => $avatar,
                        'url' => config("app.url") . 'search/instructors?_q=' . $q
                    ]
                );
            }
        }

        $results['course'] = [
            'name' => 'Match with Course',
            'results' => $instructors
        ];

        $data['results'] = $results;

        return response()->json($data);
    }
}
