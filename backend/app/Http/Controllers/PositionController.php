<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
  public function index()
  {
    $positions = Position::withCount('employees')
      ->orderBy('created_at', 'desc')
      ->get();

    return response()->json(
      [
        'positions' => $positions,
      ],
      200,
    );
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'name' => 'required|string|max:255',
      'code' => 'required|string|max:50|unique:positions,code',
      'level' => 'required|in:staff,supervisor,manager,director,executive',
      'description' => 'nullable|string',
      'is_active' => 'boolean',
    ]);

    $position = Position::create($data);

    return response()->json(
      [
        'message' => 'Position created successfully',
        'position' => $position,
      ],
      201,
    );
  }

  public function show($id)
  {
    $position = Position::withCount('employees')->find($id);

    if (!$position) {
      return response()->json(
        [
          'message' => 'Position not found',
        ],
        404,
      );
    }

    return response()->json(
      [
        'position' => $position,
      ],
      200,
    );
  }

  public function update(Request $request, $id)
  {
    $position = Position::find($id);

    if (!$position) {
      return response()->json(
        [
          'message' => 'Position not found',
        ],
        404,
      );
    }

    $data = $request->validate([
      'name' => 'required|string|max:255',
      'code' => 'required|string|max:50|unique:positions,code,' . $id,
      'level' => 'required|in:staff,supervisor,manager,director,executive',
      'description' => 'nullable|string',
      'is_active' => 'boolean',
    ]);

    $position->update($data);

    return response()->json([
      'message' => 'Position updated successfully',
      'position' => $position,
    ]);
  }

  public function destroy($id)
  {
    $position = Position::find($id);

    if (!$position) {
      return response()->json(
        [
          'message' => 'Position not found',
        ],
        404,
      );
    }

    // Check if position has employees
    if ($position->employees()->count() > 0) {
      return response()->json(
        [
          'message' => 'Cannot delete position with existing employees',
        ],
        422,
      );
    }

    $position->delete();

    return response()->json([
      'message' => 'Position deleted successfully',
    ]);
  }

  // Get active positions only (for dropdowns)
  public function active()
  {
    $positions = Position::where('is_active', true)
      ->orderBy('name')
      ->get(['id', 'name', 'code', 'level']);

    return response()->json([
      'positions' => $positions,
    ]);
  }
}
