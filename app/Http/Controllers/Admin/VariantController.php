<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function index(Request $request, Product $product)
    {
        $variants = $product->variants()->latest()->paginate(10);

        return view('admin.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product)
    {
        return view('admin.variants.create', compact('product'));
    }

    public function store(VariantRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['product_id'] = $product->id;

        ProductVariant::create($data);

        return redirect()->route('admin.products.variants.index', $product->id)
            ->with('success', 'Variant created successfully!');
    }

    public function edit(Product $product, ProductVariant $variant)
    {
        return view('admin.variants.edit', compact('product', 'variant'));
    }

    public function update(VariantRequest $request, Product $product, ProductVariant $variant)
    {
        $variant->update($request->validated());

        return redirect()->route('admin.products.variants.index', $product->id)
            ->with('success', 'Variant updated successfully!');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        $variant->delete();

        return redirect()->route('admin.products.variants.index', $product->id)
            ->with('success', 'Variant deleted successfully!');
    }
}
