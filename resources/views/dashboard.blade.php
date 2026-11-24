<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Dashboard</h2></x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @forelse($byCategory as $c)
                    <div class="bg-white shadow-sm sm:rounded-lg p-4">
                        <div class="text-sm text-gray-500">{{ $c->category }}</div>
                        <div class="text-2xl font-bold">{{ $c->total }}</div>
                    </div>
                @empty
                    <div class="col-span-full text-gray-500">Todavía no hay productos.</div>
                @endforelse
            </div>
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <h3 class="p-4 font-semibold">Stock actual</h3>
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr><th class="p-3">SKU</th><th class="p-3">Nombre</th><th class="p-3">Categoría</th><th class="p-3">Stock</th></tr>
                    </thead>
                    <tbody>
                    @foreach($products as $p)
                        <tr class="border-t" data-id="{{ $p->id }}">
                            <td class="p-3">{{ $p->sku }}</td><td class="p-3">{{ $p->name }}</td>
                            <td class="p-3">{{ $p->category }}</td><td class="p-3 font-semibold stock">{{ $p->stock }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <h3 class="p-4 font-semibold">Últimos movimientos</h3>
                <table class="min-w-full text-sm"><tbody>
                @forelse($latest as $m)
                    <tr class="border-t">
                        <td class="p-3">{{ \Carbon\Carbon::parse($m->date)->format('d/m/Y') }}</td>
                        <td class="p-3">{{ ucfirst($m->type) }}</td>
                        <td class="p-3">{{ $m->product->name }}</td>
                        <td class="p-3">{{ $m->quantity }}</td>
                    </tr>
                @empty
                    <tr><td class="p-4 text-gray-500">Sin movimientos</td></tr>
                @endforelse
                </tbody></table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.Echo) return;
            window.Echo.channel('stock').listen('StockUpdated', e => {
                const celda = document.querySelector(`tr[data-id="${e.product.id}"] .stock`);
                if (celda) celda.textContent = e.product.stock;
            });
        });
    </script>
</x-app-layout>