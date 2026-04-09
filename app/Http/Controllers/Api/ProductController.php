<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('categories');

        if ($request->has('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        $cached = Cache::has('products_list');
        $products = Cache::remember(
            'products_list',
            300,
            fn() =>
            $query->latest()->get()
        );

        //accessor
            // ->each(fn($p) => $p->name = $p->formatted_name);
            // triggers the mutator
            // ->each(fn($p) => $p->name = $p->name);
        // local scope
        // $products = $query->price(5,1000)->get();

        return ProductResource::collection($products)
            ->response()
            ->header('X-Cache', $cached ? 'HIT' : 'MISS');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest  $request)
    {

        $slug = Str::slug($request->name);
        if (Product::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }
       $product = Product::create([
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'sku'         => $request->sku,
            'is_active'   => $request->boolean('is_active', true),
            'image'       => $request->hasFile('image')
                                ? $request->file('image')->store('products', 'public')
                                : null,
        ]);
        if ($request->has('categories')) {
            // sync() replaces all existing relationships with the new ones
            $product->categories()->attach($request->categories);
        }

       $product->load('categories');

        return $this->success(new ProductResource($product), 'Product created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'  => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'sku'   => 'sometimes|string|unique:products,sku,' . $product->id,
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->has('name')) {
            $slug = Str::slug($request->name);
            if (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug .= '-' . time();
            }
            $request->merge(['slug' => $slug]);
        }

        // ← add image handling
        if ($request->hasFile('image')) {
            // delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
            $product->save();
        }

        $product->update($request->only([
            'name',
            'slug',
            'description',
            'price',
            'stock',
            'sku',
            'is_active'
        ]));
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        $product->image = $product->image_url;
        $product->load('categories');

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {

        $product = $product->delete();
        return response()->json($product, 200,);
    }

    public function undoDelete(Request $request, $id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        return response()->json([
            'success' => true,
            'message' => 'Product restored successfully',
        ], 200);
    }

    public function permanentDelete(Request $request, $id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->forceDelete();
        return response()->json([
            'success' => true,
            'message' => 'Product permanently deleted successfully',
        ], 200);
    }

    public function adminIndex()
    {
        $products = Product::withTrashed()->get();
        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products
        ], 200);
    }

    public function filter(Request $request)
    {

        $products = Product::query()
            ->when($request->filled('price_min'), fn($q) => $q->where('price', '>=', $request->price_min))
            ->when($request->filled('price_max'), fn($q) => $q->where('price', '<=', $request->price_max))
            ->when($request->filled('stock_min'), fn($q) => $q->where('stock', '>=', $request->stock_min))
            ->when(
                $request->filled('name'),
                fn($q) => $q->where('name', 'like', "%{$request->name}%")

            )
            ->latest()
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products
        ], 200);
    }
}
