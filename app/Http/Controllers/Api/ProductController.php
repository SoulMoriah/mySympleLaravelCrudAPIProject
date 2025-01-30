<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::get();

        if ($products && count($products) > 0) {
            return response()->json([
                'products' => $products
            ], 200);
            // return ProductResource::collection($products);
        }else {
            return response()->json([
                'message' => 'No products found'
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductResource($product)
        ], 201);
    }

    public function show(Product $product)
    {
        return response()->json([
            'product' => new ProductResource($product)
        ], 200);
    }	

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Product Updated successfully',
            'data' => new ProductResource($product)
        ], 201);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'message' => 'Product Deleted successfully'
        ], 201);
    }

    public function search($name)
    {
        $products = Product::where('name', 'like', '%' . $name . '%')->get();
        if ($products && count($products) > 0) {
            // return response()->json([
            //     'products' => $products
            // ], 200);
            return ProductResource::collection($products);
        }else {
            return response()->json([
                'message' => 'No products found'
            ], 200);
        }
    }
}
