<?php
namespace App\Services;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseService
{
    public function createCourse(array $data)
    {
        $course = Course::create($data);

        return $course;
    }
    
    public function uploadMedia(Course $course)
    {
        $media = $course->addMediaFromRequest('file')->toMediaCollection('media');

        return $media;
    }

    public function getMedia(Course $course)
    {
        $media = $course->getMedia('media');

        $mediaUrls = $media->map(function ($item) {
            return $item->getUrl();
        });

        return $mediaUrls;
    }

    public function showCourse($id)
    {
        return Course::findOrFail($id);
    }

    public function editCourse($id)
    {
        return Course::findOrFail($id);
    }
    
    public function updateCourse(Course $course, array $data)
    {
        $course->update($data);

        return $course;
    }

    public function deleteCourse(Course $course)
    {
        $course->delete();
    }
}