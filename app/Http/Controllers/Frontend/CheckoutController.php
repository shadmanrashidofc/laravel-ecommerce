<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    protected function getCart(): array
    {
        return Session::get('cart', []);
    }

    public function index()
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        $productIds = array_column($cart, 'product_id');
        $variantIds = array_filter(array_column($cart, 'variant_id'));

        $products = Product::with('images')
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $variants = ! empty($variantIds)
            ? ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id')
            : collect();

        $cartItems = [];
        $total = 0;

        foreach ($cart as $key => $item) {
            $product = $products->get($item['product_id']);
            if ($product) {
                $variant = $item['variant_id'] ? $variants->get($item['variant_id']) : null;
                $price = $item['price'];
                $subtotal = $price * $item['quantity'];
                $cartItems[] = [
                    'key' => $key,
                    'product' => $product,
                    'variant' => $variant,
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        if (empty($cartItems)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        return view('frontend.checkout.index', compact('cartItems', 'total'));
    }

    public function placeOrder(CheckoutRequest $request)
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($cart as $key => $item) {
                $product = Product::find($item['product_id']);

                if (! $product) {
                    continue;
                }

                if ($product->status !== 'active') {
                    DB::rollBack();

                    return redirect()->back()->with('error', "Product no longer available: {$product->name}");
                }

                $variant = null;
                if ($item['variant_id']) {
                    $variant = ProductVariant::where('id', $item['variant_id'])
                        ->where('product_id', $item['product_id'])
                        ->first();

                    if (! $variant) {
                        DB::rollBack();

                        return redirect()->back()->with('error', "Invalid variant for product: {$product->name}");
                    }

                    if ($variant->stock < $item['quantity']) {
                        DB::rollBack();

                        return redirect()->back()->with('error', "Not enough stock for product: {$product->name}. Available: {$variant->stock}");
                    }

                    $variant->decrement('stock', $item['quantity']);
                } else {
                    if ($product->stock !== null && $product->stock < $item['quantity']) {
                        DB::rollBack();

                        return redirect()->back()->with('error', "Not enough stock for product: {$product->name}. Available: {$product->stock}");
                    }

                    if ($product->stock !== null) {
                        $product->decrement('stock', $item['quantity']);
                    }
                }

                $price = $item['price'];
                $subtotal = $price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name_snapshot' => $product->name,
                    'variant_snapshot' => $variant ? [
                        'sku' => $variant->sku,
                        'size' => $variant->size,
                        'color' => $variant->color,
                    ] : null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $price,
                    'subtotal' => $subtotal,
                ];
            }

            if (empty($orderItemsData)) {
                DB::rollBack();

                return redirect()->route('cart')->with('error', 'No valid items in cart!');
            }

            $orderNumber = $this->generateOrderNumber();

            $order = Order::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'order_number' => $orderNumber,
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
            ]);

            foreach ($orderItemsData as $itemData) {
                OrderItem::create(array_merge($itemData, ['order_id' => $order->id]));
            }

            Session::forget('cart');

            DB::commit();

            return redirect()->route('order.success', $orderNumber)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    protected function generateOrderNumber(): string
    {
        $year = date('Y');
        $prefix = "ORD-{$year}-";

        $lockKey = "order_number_generation_{$year}";

        return Cache::lock($lockKey, 10)->block(5, function () use ($prefix) {
            $lastOrder = Order::where('order_number', 'like', $prefix.'%')
                ->lockForUpdate()
                ->orderBy('order_number', 'desc')
                ->first();

            if ($lastOrder) {
                $lastNumber = (int) substr($lastOrder->order_number, -5);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            return $prefix.str_pad((string) $newNumber, 5, '0', STR_PAD_LEFT);
        });
    }

    public function success(string $orderNumber)
    {
        $order = Order::with('items.product', 'items.variant')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('frontend.checkout.success', compact('order'));
    }
}
