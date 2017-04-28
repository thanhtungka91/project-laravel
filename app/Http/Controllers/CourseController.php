<?php

namespace App\Http\Controllers;
use Illuminate\Auth\Access\Response;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::all();
//        dd($courses);
        return view('course.list',[
            'courses' => $courses
        ]);
    }

    public function create(Request $request){
        $course = new Course();
        $course->course_name = $request->course_name;
        $course->subject_name = $request->subject_name;
        $course->subject_overview = $request->subject_overview;
        $course->instructor_name = $request->instructor_name;
        $course->video = $request->video;
        $course->thumbnail = $request->thumbnail;
        $course->save();
        return redirect()->route('course.done', ['id' => $course->id]);
    }

    public function add()
    {
        return view('course.add');
    }
    public function doneRegister()
    {
        return view('course.done');
    }

}
