<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    protected function getCart(): array
    {
        return Session::get('cart', []);
    }

    protected function saveCart(array $cart): void
    {
        Session::put('cart', $cart);
    }

    public function index()
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return view('frontend.cart.index', ['cartItems' => [], 'total' => 0]);
        }

        $productIds = array_column($cart, 'product_id');
        $variantIds = array_filter(array_column($cart, 'variant_id'));

        $products = Product::with('images')
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $variants = ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id');

        $cartItems = [];
        $total = 0;

        foreach ($cart as $key => $item) {
            $product = $products->get($item['product_id']);

            if (! $product) {
                unset($cart[$key]);

                continue;
            }

            $variant = $item['variant_id'] ? $variants->get($item['variant_id']) : null;

            $price = $item['price'];
            $quantity = $item['quantity'];
            $subtotal = $price * $quantity;

            $cartItems[] = [
                'key' => $key,
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'variant_id' => $item['variant_id'] ?? null,
                'size' => $item['size'] ?? null,
                'color' => $item['color'] ?? null,
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'image' => $item['image'],
                'product' => $product,
                'variant' => $variant,
            ];

            $total += $subtotal;
        }

        $this->saveCart($cart);

        return view('frontend.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $product = Product::with('images')->findOrFail($request->product_id);

        if ($product->status !== 'active') {
            return back()->with('error', 'This product is not available!');
        }

        $price = $product->discount_price ?? $product->price;
        $size = null;
        $color = null;
        $image = $product->images->first()?->image_path ?? null;
        $stock = null;

        if ($request->variant_id) {
            $variant = ProductVariant::where('id', $request->variant_id)
                ->where('product_id', $request->product_id)
                ->first();

            if (! $variant) {
                return back()->with('error', 'Invalid product variant selected!');
            }

            $stock = $variant->stock;
            $price = $variant->price ?? $price;
            $size = $variant->size;
            $color = $variant->color;

            if ($stock < $request->quantity) {
                return back()->with('error', 'Only '.$stock.' items available in stock!');
            }

            $cart = $this->getCart();
            $existingQty = 0;
            $key = 'variant_'.$request->variant_id;

            if (isset($cart[$key])) {
                $existingQty = $cart[$key]['quantity'];
            }

            if (($existingQty + $request->quantity) > $stock) {
                return back()->with('error', 'Only '.$stock.' items available in stock!');
            }
        } else {
            $stock = $product->stock ?? null;

            if ($stock !== null && $stock < $request->quantity) {
                return back()->with('error', 'Only '.$stock.' items available in stock!');
            }

            $cart = $this->getCart();
            $existingQty = 0;
            $key = 'product_'.$request->product_id;

            if (isset($cart[$key])) {
                $existingQty = $cart[$key]['quantity'];
            }

            if ($stock !== null && ($existingQty + $request->quantity) > $stock) {
                return back()->with('error', 'Only '.$stock.' items available in stock!');
            }
        }

        $cart = $this->getCart();

        if ($request->variant_id) {
            $key = 'variant_'.$request->variant_id;
        } else {
            $key = 'product_'.$request->product_id;
        }

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity;
            $cart[$key]['subtotal'] = $cart[$key]['price'] * $cart[$key]['quantity'];
        } else {
            $cart[$key] = [
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
                'product_name' => $product->name,
                'size' => $size,
                'color' => $color,
                'price' => $price,
                'quantity' => $request->quantity,
                'subtotal' => $price * $request->quantity,
                'image' => $image,
            ];
        }

        $this->saveCart($cart);

        return redirect()->route('cart')->with('success', 'Product added to cart!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        $cart = $this->getCart();

        if (! isset($cart[$request->key])) {
            return redirect()->route('cart')->with('error', 'Item not found in cart!');
        }

        if ($request->quantity <= 0) {
            unset($cart[$request->key]);
        } else {
            $item = $cart[$request->key];

            if ($item['variant_id']) {
                $variant = ProductVariant::find($item['variant_id']);
                if ($variant && $request->quantity > $variant->stock) {
                    return redirect()->route('cart')->with('error', 'Only '.$variant->stock.' items available!');
                }
            } else {
                $product = Product::find($item['product_id']);
                if ($product && $product->stock !== null && $request->quantity > $product->stock) {
                    return redirect()->route('cart')->with('error', 'Only '.$product->stock.' items available!');
                }
            }

            $cart[$request->key]['quantity'] = $request->quantity;
            $cart[$request->key]['subtotal'] = $item['price'] * $request->quantity;
        }

        $this->saveCart($cart);

        return redirect()->route('cart')->with('success', 'Cart updated!');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $cart = $this->getCart();

        if (! isset($cart[$request->key])) {
            return redirect()->route('cart')->with('error', 'Item not found in cart!');
        }

        unset($cart[$request->key]);
        $this->saveCart($cart);

        return redirect()->route('cart')->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        Session::forget('cart');

        return redirect()->route('cart')->with('success', 'Cart cleared!');
    }
}
