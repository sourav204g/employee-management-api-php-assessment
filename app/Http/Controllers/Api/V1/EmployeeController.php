<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index(Department $department)
    {
        // $departmentId = $request->route('department');
        $department = Department::find($department->id);

        if (!$department) {
            return response()->json(['message' => 'Department not found.'], 404);
        }
    
        $employees = $department->employees;
        
        return response()->json(['employees' => $employees], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Department $department)
    {
        $department = Department::find($department->id);

        if (!$department) {
            return response()->json(['message' => 'Department not found.'], 404);
        }

        $employee = new Employee;
        $employee->name = $request->input('name');
        $employee->department_id = $department->id;
        $employee->contact_numbers = $request->input('contact_numbers');
        $employee->addresses = $request->input('addresses');
        $employee->save();

        return response()->json(['employee' => $employee], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return response()->json($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $employee->fill($request->all());
        $employee->save();
        
        return response()->json($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        
        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $query = Employee::query();

        $department_id = $request->input('department_id');

        $department = Department::find($department_id);

        if (!$department) {
            return response()->json(['message' => 'Department not found.'], 404);
        }

        $name = $request->input('name');
        $contact_number = $request->input('contact_number');

        if ($department_id) {
            $query->where('department_id', $department_id);
        }

        if ($name) {
            $query->where('name', 'like', "%{$name}%");
        }

        if ($contact_number) {
            $query->whereJsonContains('contact_numbers', $contact_number);
        }

        $employees = $query->get();

        return response()->json($employees);
    }

}
