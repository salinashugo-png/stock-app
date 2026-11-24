<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
public function index(Request $r)
{
    if ($r->wantsJson()) {
        return Product::orderBy('name')->get();
    }

    $products = Product::query()
        ->when($r->q, fn ($q, $s) => $q->where(fn ($w) => $w
            ->where('name', 'like', "%$s%")->orWhere('sku', 'like', "%$s%")))
        ->when($r->category, fn ($q, $c) => $q->where('category', $c))
        ->orderBy('name')
        ->paginate(15)
        ->withQueryString();

    $categories = Product::distinct()->orderBy('category')->pluck('category');

    return view('products.index', compact('products', 'categories'));
}

    public function stock()
    {
        return Product::select('id', 'name', 'sku', 'category', 'stock')->orderBy('name')->get();
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|max:100|unique:products,sku',
            'category' => 'required|string|max:100',
        ]);

        $product = Product::create($data);

        if ($r->wantsJson()) {
            return response()->json($product, 201);
        }

        return redirect()->route('products.index')->with('ok', 'Producto creado');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $r, Product $product)
    {
        $data = $r->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|max:100|unique:products,sku,'.$product->id,
            'category' => 'required|string|max:100',
        ]);

        $product->update($data);

        if ($r->wantsJson()) {
            return response()->json($product);
        }

        return redirect()->route('products.index')->with('ok', 'Producto actualizado');
    }

    public function destroy(Request $r, Product $product)
    {
        $product->delete();

        if ($r->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('products.index')->with('ok', 'Producto eliminado');
    }
}