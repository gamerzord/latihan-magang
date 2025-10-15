<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
  public function index()
  {
    return response()->json(Product::all(), 200);
  }

  public function show(Product $product)
  {
    return response()->json($product, 200);
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'name' => 'required',
      'qty' => 'required|numeric',
      'price' => 'required|numeric',
      'description' => 'nullable|string',
    ]);

    $product = Product::create($data);
    return response()->json(
      ['message' => 'Product created successfully', 'product' => $product],
      201,
    );
  }

  public function update(Request $request, Product $product)
  {
    $data = $request->validate([
      'name' => 'required',
      'qty' => 'required|numeric',
      'price' => 'required|numeric',
      'description' => 'nullable|string',
    ]);

    $product->update($data);
    return response()->json(
      [
        'message' => 'Product updated successfully',
        'product' => $product,
      ],
      200,
    );
  }

  public function destroy(Product $product)
  {
    $product->delete();
    return response()->json(['message' => 'Product deleted successfully'], 200);
  }
}
