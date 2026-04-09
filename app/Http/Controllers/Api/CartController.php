<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)->get();
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        // Add subtotal for each cart item
    foreach ($cartItems as $item) {
        $item->subtotal = $item->product->price * $item->quantity;
    }

         $cartItems->load('product');
        return response()->json([
            'success' => true,
            'cart' => $cartItems,
            'total' => $total,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = $request->user();
        $cartItem =
            Cart::where('user_id', $user->id)
            ->where('product_id', $data['product_id'])
            ->first();
        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
        } else {
            $cartItem = Cart::create([
                'product_id' => $data['product_id'],
                'user_id' => $user->id,
                'quantity' => $data['quantity'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart_item' => $cartItem
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Cart $cart)
    {
        // Check if the cart item belongs to the authenticated user
        if ($cart->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $cart->load('product');

        return response()->json([
            'success' => true,
            'cart_item' => $cart
        ]);

        }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
         // Ensure user owns this cart item
        if ($cart->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
          if ($request->has('product_id')) {
        return response()->json([
            'error' => 'Cannot change product_id',
            'message' => 'Product ID cannot be modified'
        ], 422);
    }

        $data = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->update(['quantity' => $data['quantity']]);
        $cart->subtotal = $cart->product->price * $cart->quantity;
       $cart->load('product');

        return response()->json([
            'success' => true,
            'message' => 'Cart updated',
            'cart_item' => $cart
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart'
        ]);
    }
}
