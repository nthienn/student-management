<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Resources\ClassesResource;
use App\Http\Resources\StudentResource;
use App\Models\Classes;
use App\Models\Student;
use Gate;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('student_access');

        $studentsQuery = Student::search($request);
        $classes = ClassesResource::collection(Classes::all());

        // $this->applySearch($studentsQuery, $request->search);    

        $students = StudentResource::collection($studentsQuery->paginate(10));

        return inertia('Students/Index', [
            'students' => $students,
            'classes' => $classes,
            'class_id' => $request->class_id ?? '',
            'search' => $request->search ?? ''
        ]);
    }

    // protected function applySearch($query, $search)
    // {
    //     return $query->when($search, function ($query, $search) {
    //         $query->where('name', 'like', '%' . $search . '%')
    //             ->orWhere('email', 'like', '%' . $search . '%');
    //     });
    // }

    public function create()
    {
        Gate::authorize('student_create');

        $classes = ClassesResource::collection(Classes::all());

        return inertia('Students/Create', [
            'classes' => $classes
        ]);
    }

    public function store(StudentStoreRequest $request)
    {
        Gate::authorize('student_create');

        Student::create($request->validated());

        return redirect()->route('students.index');
    }

    public function edit(Student $student)
    {
        Gate::authorize('student_edit');

        $classes = ClassesResource::collection(Classes::all());

        return inertia('Students/Edit', [
            'classes' => $classes,
            'student' => StudentResource::make($student)
        ]);
    }

    public function update(StudentUpdateRequest $request, Student $student)
    {
        Gate::authorize('student_edit');

        $student->update($request->validated());

        return redirect()->route('students.index');
    }

    public function destroy(Student $student)
    {
        Gate::authorize('student_delete');

        $student->delete();

        return redirect()->route('students.index');
    }
}