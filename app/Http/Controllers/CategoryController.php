<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class  CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('parent')->latest()->get();
        return response()->json($categories, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',

            'parent_id' => 'nullable|exists:categories,id', // Make sure parent exists if provided
        ]);
        $slug = Str::slug($request->name);



        $category = Category::create([
            'name'        => $request->name,

            'parent_id'   => $request->parent_id,
            'slug'        => $slug,
            'is_active'   => true,
        ]);

        $category->load('parent');
        return response()->json([
            'message'  => 'Category created successfully',
            'category' => $category,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        // Load the parent and children relationships
        $category->load(['parent', 'children']);

        return response()->json([
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active'   => 'sometimes|boolean',
            'parent_id'   => 'nullable|exists:categories,id',
        ]);

        if ($request->parent_id == $category->id) {
            return response()->json([
                'message' => 'A category cannot be its own parent',
            ], 422);
        }
        if ($request->has('name')) {
            $request->merge(['slug' => Str::slug($request->name)]);
        }
        $category->load('parent');

        $category->update($request->only([
            'name',
            'slug',
            'description',
            'parent_id',
            'is_active'
        ]));

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->children()->update(['parent_id' => $category->parent_id]);

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully',
        ]);
        //
    }
    public function products(Category $category)
    {
        $category = $category->load('products');



        return response()->json([
            'message' => ' successfully',
            'data' => $category,
        ]);
        //
    }
}
