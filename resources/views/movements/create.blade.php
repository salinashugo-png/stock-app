<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Registrar movimiento</h2></x-slot>
    <div class="py-8">
        <div class="max-w-2xl mx-auto bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('movements.store') }}" class="space-y-4" x-data="{ tipo: '{{ old('type', 'entrada') }}' }">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Producto</label>
                    <select name="product_id" class="mt-1 w-full border-gray-300 rounded-md" required>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" @selected(old('product_id') == $p->id)>{{ $p->sku }} - {{ $p->name }} (stock: {{ $p->stock }})</option>
                        @endforeach
                    </select>
                    @error('product_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo</label>
                    <select name="type" x-model="tipo" class="mt-1 w-full border-gray-300 rounded-md">
                        <option value="entrada">Entrada</option>
                        <option value="salida">Salida</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                    <input type="number" min="1" name="quantity" value="{{ old('quantity') }}" class="mt-1 w-full border-gray-300 rounded-md" required>
                    @error('quantity')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha</label>
                    <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}" class="mt-1 w-full border-gray-300 rounded-md" required>
                </div>
                <div x-show="tipo === 'entrada'">
                    <label class="block text-sm font-medium text-gray-700">Proveedor</label>
                    <input type="text" name="supplier" value="{{ old('supplier') }}" class="mt-1 w-full border-gray-300 rounded-md">
                    @error('supplier')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div x-show="tipo === 'salida'">
                    <label class="block text-sm font-medium text-gray-700">Motivo</label>
                    <input type="text" name="reason" value="{{ old('reason') }}" class="mt-1 w-full border-gray-300 rounded-md">
                    @error('reason')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-md">Registrar</button>
                <a href="{{ route('movements.index') }}" class="px-4 py-2 text-gray-600">Cancelar</a>
            </form>
        </div>
    </div>
</x-app-layout>