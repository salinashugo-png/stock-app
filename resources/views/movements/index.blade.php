<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Movimientos</h2>
            <a href="{{ route('movements.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">Nuevo movimiento</a>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('ok'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('ok') }}</div>@endif
            <form method="GET" class="mb-4 flex flex-wrap gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Producto o SKU" class="border-gray-300 rounded-md text-sm">
                <select name="type" class="border-gray-300 rounded-md text-sm">
                    <option value="">Todos</option>
                    <option value="entrada" @selected(request('type') === 'entrada')>Entradas</option>
                    <option value="salida" @selected(request('type') === 'salida')>Salidas</option>
                </select>
                <input type="date" name="from" value="{{ request('from') }}" class="border-gray-300 rounded-md text-sm">
                <input type="date" name="to" value="{{ request('to') }}" class="border-gray-300 rounded-md text-sm">
                <button class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">Filtrar</button>
            </form>
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr><th class="p-3">Fecha</th><th class="p-3">Tipo</th><th class="p-3">Producto</th><th class="p-3">Cantidad</th><th class="p-3">Proveedor / Motivo</th><th class="p-3">Usuario</th></tr>
                    </thead>
                    <tbody>
                    @forelse($movements as $m)
                        <tr class="border-t">
                            <td class="p-3">{{ \Carbon\Carbon::parse($m->date)->format('d/m/Y') }}</td>
                            <td class="p-3 font-semibold {{ $m->type === 'entrada' ? 'text-green-700' : 'text-red-700' }}">{{ ucfirst($m->type) }}</td>
                            <td class="p-3">{{ $m->product->sku }} - {{ $m->product->name }}</td>
                            <td class="p-3">{{ $m->quantity }}</td>
                            <td class="p-3">{{ $m->supplier ?? $m->reason }}</td>
                            <td class="p-3">{{ $m->user->name }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-4 text-center text-gray-500">Sin movimientos</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $movements->links() }}</div>
        </div>
    </div>
</x-app-layout>