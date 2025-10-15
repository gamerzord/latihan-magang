<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
  public function index(Request $request)
  {
    $query = Employee::with(['division', 'position']);

    // Filter by division
    if ($request->has('division_id')) {
      $query->where('division_id', $request->division_id);
    }

    // Filter by position
    if ($request->has('position_id')) {
      $query->where('position_id', $request->position_id);
    }

    // Filter by status
    if ($request->has('status')) {
      $query->where('status', $request->status);
    }

    // Search by name or email
    if ($request->has('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%")
          ->orWhere('employee_code', 'like', "%{$search}%");
      });
    }

    $employees = $query->orderBy('created_at', 'desc')->get();

    return response()->json([
      'employees' => $employees,
    ]);
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'employee_code' =>
        'required|string|max:50|unique:employees,employee_code',
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:employees,email',
      'phone' => 'nullable|string|max:20',
      'division_id' => 'required|exists:divisions,id',
      'position_id' => 'required|exists:positions,id',
      'join_date' => 'required|date',
      'status' => 'required|in:active,inactive,resigned',
      'address' => 'nullable|string',
    ]);

    $employee = Employee::create($data);
    $employee->load(['division', 'position']);

    return response()->json(
      [
        'message' => 'Employee added successfully',
        'employee' => $employee,
      ],
      201,
    );
  }

  public function show($id)
  {
    $employee = Employee::with(['division', 'position'])->find($id);

    if (!$employee) {
      return response()->json(
        [
          'message' => 'Employee not found',
        ],
        404,
      );
    }

    return response()->json(
      [
        'employee' => $employee,
      ],
      200,
    );
  }

  public function update(Request $request, $id)
  {
    $employee = Employee::find($id);

    if (!$employee) {
      return response()->json(
        [
          'message' => 'Employee not found',
        ],
        404,
      );
    }

    $data = $request->validate([
      'employee_code' =>
        'required|string|max:50|unique:employees,employee_code,' . $id,
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:employees,email,' . $id,
      'phone' => 'nullable|string|max:20',
      'division_id' => 'required|exists:divisions,id',
      'position_id' => 'required|exists:positions,id',
      'join_date' => 'required|date',
      'status' => 'required|in:active,inactive,resigned',
      'address' => 'nullable|string',
    ]);

    $employee->update($data);
    $employee->load(['division', 'position']);

    return response()->json(
      [
        'message' => 'Employee updated successfully',
        'employee' => $employee,
      ],
      200,
    );
  }

  public function destroy($id)
  {
    $employee = Employee::find($id);

    if (!$employee) {
      return response()->json(
        [
          'message' => 'Employee not found',
        ],
        404,
      );
    }

    $employee->delete();

    return response()->json(
      [
        'message' => 'Employee deleted successfully',
      ],
      200,
    );
  }
}
