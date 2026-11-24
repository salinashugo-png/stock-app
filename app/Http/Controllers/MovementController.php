<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MovementController extends Controller
{
    public function index(Request $r)
    {
        $movements = Movement::with('product', 'user')
            ->when($r->type, fn ($q, $t) => $q->where('type', $t))
            ->when($r->q, fn ($q, $s) => $q->whereHas('product', fn ($p) => $p
                ->where('name', 'like', "%$s%")->orWhere('sku', 'like', "%$s%")))
            ->when($r->from, fn ($q, $d) => $q->whereDate('date', '>=', $d))
            ->when($r->to, fn ($q, $d) => $q->whereDate('date', '<=', $d))
            ->latest('date')->latest('id')
            ->paginate(15)
            ->withQueryString();

        if ($r->wantsJson()) {
            return $movements;
        }

        return view('movements.index', compact('movements'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('movements.create', compact('products'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:entrada,salida',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'supplier' => 'required_if:type,entrada|nullable|string|max:255',
            'reason' => 'required_if:type,salida|nullable|string|max:255',
        ]);

        $product = DB::transaction(function () use ($data, $r) {
            $p = Product::lockForUpdate()->findOrFail($data['product_id']);

            if ($data['type'] === 'salida' && $p->stock < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => "Stock insuficiente (disponible: {$p->stock})",
                ]);
            }

            $data['type'] === 'entrada'
                ? $p->increment('stock', $data['quantity'])
                : $p->decrement('stock', $data['quantity']);

            Movement::create($data + ['user_id' => $r->user()->id]);

            return $p->fresh();
        });

        if ($r->wantsJson()) {
            return response()->json($product, 201);
        }

        return redirect()->route('movements.index')->with('ok', 'Movimiento registrado');
    }
}
