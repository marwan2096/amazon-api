<?php

namespace App\Http\Controllers\Api;

use App\Enum\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
     public function checkout(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_zipcode' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:credit_card,paypal',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty'], 400);
        }

        $subtotal = 0;
        $items = [];

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;

            if (!$product->is_active) {
                return response()->json(['message' => "Product '{$product->name}' is no longer available"], 400);
            }

            if ($product->stock < $cartItem->quantity) {
                return response()->json(['message' => "Not enough stock for '{$product->name}'"], 400);
            }

            $itemSubtotal = $product->price * $cartItem->quantity;
            $subtotal += $itemSubtotal;

            $items[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'quantity' => $cartItem->quantity,
                'price' => $product->price,
                'subtotal' => $itemSubtotal,
            ];

        }

        $tax = round($subtotal * 0.08, 2);
        $shippingCost = 5.00;
        $total = $subtotal + $tax + $shippingCost;

        DB::beginTransaction();
        try {
            $order = new Order([
                'user_id' => $user->id,
                'status' => OrderStatus::PENDING,
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zipcode' => $request->shipping_zipcode,
                'shipping_country' => $request->shipping_country,
                'shipping_phone' => $request->shipping_phone,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => PaymentStatus::PENDING,
                'order_number' => Order::generateOrderNumber(),
                'notes' => $request->notes,
            ]);

            $user->orders()->save($order);

            foreach ($items as $item) {
                $order->items()->create($item);
                Product::find($item['product_id'])->decrement('stock', $item['quantity']);
            }

            $user->cartItems()->delete();
            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order->load('items'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to place order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function orderHistory(Request $request)
    {
        $orders=$request->user()->orders()->with('items')->orderBy('created_at','desc')->get();
          return response()->json([
                'message' => 'order done',
                'orders'=>$orders
            ], 500);
    }
    public function orderDetails(Request $request,Order $order)
    {
         // Check if order belongs to user
    if ($order->user_id !== $request->user()->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    $order->load('items');
          return response()->json([
                'message' => 'order done',
                'order'=>$order
            ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
