<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items.product')
            ->latest()
            ->get();

        return view('profile.index', [
            'user' => $request->user(),
            'orders' => $orders,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function reorder(Request $request, Order $order): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        foreach ($order->items as $item) {
            $product = Product::with('images')->find($item->product_id);
            if (! $product || $product->status !== 'active') {
                continue;
            }

            $variantId = $item->product_variant_id;
            $key = $variantId ? 'variant_'.$variantId : 'product_'.$item->product_id;

            $variantSnapshot = $item->variant_snapshot;
            $size = $variantSnapshot['size'] ?? null;
            $color = $variantSnapshot['color'] ?? null;
            $price = $item->unit_price;
            $image = $product->images->first()?->image_path ?? null;

            if (isset($cart[$key])) {
                $cart[$key]['quantity'] += $item->quantity;
                $cart[$key]['subtotal'] = $cart[$key]['price'] * $cart[$key]['quantity'];
            } else {
                $cart[$key] = [
                    'product_id' => $item->product_id,
                    'variant_id' => $variantId,
                    'product_name' => $item->product_name_snapshot,
                    'size' => $size,
                    'color' => $color,
                    'price' => $price,
                    'quantity' => $item->quantity,
                    'subtotal' => $price * $item->quantity,
                    'image' => $image,
                ];
            }
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('profile.page')->with('success', 'Items added to cart!');
    }

    public function reorderAndCheckout(Request $request, Order $order): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        foreach ($order->items as $item) {
            $product = Product::with('images')->find($item->product_id);
            if (! $product || $product->status !== 'active') {
                continue;
            }

            $variantId = $item->product_variant_id;
            $key = $variantId ? 'variant_'.$variantId : 'product_'.$item->product_id;

            $variantSnapshot = $item->variant_snapshot;
            $size = $variantSnapshot['size'] ?? null;
            $color = $variantSnapshot['color'] ?? null;
            $price = $item->unit_price;
            $image = $product->images->first()?->image_path ?? null;

            if (isset($cart[$key])) {
                $cart[$key]['quantity'] += $item->quantity;
                $cart[$key]['subtotal'] = $cart[$key]['price'] * $cart[$key]['quantity'];
            } else {
                $cart[$key] = [
                    'product_id' => $item->product_id,
                    'variant_id' => $variantId,
                    'product_name' => $item->product_name_snapshot,
                    'size' => $size,
                    'color' => $color,
                    'price' => $price,
                    'quantity' => $item->quantity,
                    'subtotal' => $price * $item->quantity,
                    'image' => $image,
                ];
            }
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('checkout');
    }
}
