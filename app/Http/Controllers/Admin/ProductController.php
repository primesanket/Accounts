<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $query = Product::query();

            if ($request->has('search')) {
                $query->where('product_name', 'like', '%' . $request->search . '%');
            }

            $query->latest();

            $data = $query->paginate(10);
            // Return success response
            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $data = $request->all();

            // Store the data
            Product::create($data);

            // Return success response
            return response()->json(['message' => 'Product stored successfully!'], 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $product = Product::find($id);

            if ($product) {
                // Return success response
                return response()->json(['message' => 'Product fetch successfully!', 'data' => $product], 201);
            } else {
                // Return not found response
                return response()->json(['error' => 'Product not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductRequest $request, $id)
    {
        try {
            $product = Product::find($id);

            if ($product) {
                $data = $request->all();
                $product->update($data);

                // Return success response
                return response()->json(['message' => 'Product updated successfully!', 'data' => $product], 201);
            } else {
                // Return not found response
                return response()->json(['error' => 'Product not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if ($product) {
                $product->delete();

                // Return success response
                return response()->json(['message' => 'Product deleted successfully!'], 200);
            } else {
                // Return not found response
                return response()->json(['error' => 'Product not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
