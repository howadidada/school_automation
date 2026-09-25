<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentModel;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\ScheduleFile;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | الإحصائيات الأساسية
        |--------------------------------------------------------------------------
        */

        $stats = [
            'users'         => User::count(),
            'students'      => Student::count(),
            'teachers'      => Teacher::count(),
            'parents'       => ParentModel::count(),
            'classes'       => SchoolClass::count(),
            'sections'      => Section::count(),
            'subjects'      => Subject::count(),
            'assignments'   => TeacherAssignment::count(),
            'schedule_files'=> ScheduleFile::count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | عدد الطلاب في كل شعبة
        |--------------------------------------------------------------------------
        */

        $sectionsStats = Section::with(['schoolClass'])
            ->withCount('students')
            ->orderBy('school_class_id')
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | أعلى قيمة لاستخدامها في أعمدة الرسم
        |--------------------------------------------------------------------------
        */

        $maxSectionStudents = $sectionsStats->max('students_count');

        if (!$maxSectionStudents || $maxSectionStudents < 1) {
            $maxSectionStudents = 1;
        }


        /*
        |--------------------------------------------------------------------------
        | آخر ملف جدول تم رفعه
        |--------------------------------------------------------------------------
        */

        $latestScheduleFile = ScheduleFile::latest()
            ->first();


        return view('dashboard', compact(
            'stats',
            'sectionsStats',
            'maxSectionStudents',
            'latestScheduleFile'
        ));
    }
}