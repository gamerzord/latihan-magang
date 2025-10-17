<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;

class DivisionController extends Controller
{
  public function index()
  {
    $divisions = Division::withCount('employees')
      ->orderBy('created_at', 'desc')
      ->get();

    return response()->json(
      [
        'divisions' => $divisions,
      ],
      200,
    );
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'name' => 'required|string|max:255',
      'code' => 'required|string|max:50|unique:divisions,code',
      'description' => 'nullable|string',
      'is_active' => 'boolean',
    ]);

    $division = Division::create($data);

    return response()->json(
      [
        'message' => 'Division created successfully',
        'division' => $division,
      ],
      201,
    );
  }

  public function show($id)
  {
    $division = Division::withCount('employees')->find($id);

    if (!$division) {
      return response()->json(
        [
          'message' => 'Division not found',
        ],
        404,
      );
    }

    return response()->json(
      [
        'division' => $division,
      ],
      200,
    );
  }

  public function update(Request $request, $id)
  {
    $division = Division::find($id);

    if (!$division) {
      return response()->json(
        [
          'message' => 'Division not found',
        ],
        404,
      );
    }

    $data = $request->validate([
      'name' => 'required|string|max:255',
      'code' => 'required|string|max:50|unique:divisions,code,' . $id,
      'description' => 'nullable|string',
      'is_active' => 'boolean',
    ]);

    $division->update($data);

    return response()->json(
      [
        'message' => 'Division updated successfully',
        'division' => $division,
      ],
      200,
    );
  }

  public function destroy($id)
  {
    $division = Division::find($id);

    if (!$division) {
      return response()->json(
        [
          'message' => 'Division not found',
        ],
        404,
      );
    }

    // Check if division has employees
    if ($division->employees()->count() > 0) {
      return response()->json(
        [
          'message' => 'Cannot delete division with existing employees',
        ],
        422,
      );
    }

    $division->delete();

    return response()->json(
      [
        'message' => 'Division deleted successfully',
      ],
      200,
    );
  }

  // Get active divisions only (for dropdowns)
  public function active()
  {
    $divisions = Division::where('is_active', true)
      ->orderBy('name')
      ->get(['id', 'name', 'code']);

    return response()->json(
      [
        'divisions_active' => $divisions,
      ],
      200,
    );
  }
}
