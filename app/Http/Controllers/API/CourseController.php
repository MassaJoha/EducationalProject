<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    
    private $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index(Request $request){
        $includeDeleted = $request->query('include_deleted', false);
        $searchName = $request->query('search_name');
        $searchPackageId = $request->query('search_package_id');
    
        $courses = $includeDeleted ? Course::withTrashed()->get() : Course::all();
    
        $filteredCourses = $courses->filter(function ($course) use ($searchName, $searchPackageId) {
            return (!$searchName || stripos($course->name, $searchName) !== false) &&
                   (!$searchPackageId || $course->package_id == $searchPackageId);
        });
    
        return CourseResource::collection($filteredCourses);
    }

    // Add Media function to specific Course
    public function addMedia(Course $course){
        $this->courseService->uploadMedia($course);

        return response()->json(['message' => 'Media uploaded successfully']);
    }

    // Get all media for a specific Course
    public function getAllMediaForCourse(Course $course){
        $media = $this->courseService->getMedia($course);

        return response()->json($media);
    }

    public function show($id)
    {
        $package = $this->courseService->showCourse($id);

        return new CourseResource($package);
    }

    public function edit($id)
    {
        $package = $this->courseService->editCourse($id);

        return new CourseResource($package);
    }

    public function store(CourseRequest $request)
    {
        $package = $this->courseService->createCourse($request->validated());

        return new CourseResource($package);
    }

    public function update(CourseRequest $request, $id)
    {
        $package = Course::findOrFail($id);

        $updatedCourse = $this->courseService->updateCourse($package, $request->validated());

        return new CourseResource($updatedCourse);
    }

    public function destroy(Course $course)
    {
        $this->courseService->deleteCourse($course);

        return response()->json(['message' => 'Course deleted successfully']);
    }

}
