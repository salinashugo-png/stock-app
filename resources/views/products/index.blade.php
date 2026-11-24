<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Productos</h2>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('products.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">Nuevo producto</a>
            @endif
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('ok'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('ok') }}</div>@endif
            <form method="GET" class="mb-4 flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Nombre o SKU" class="border-gray-300 rounded-md text-sm">
                <select name="category" class="border-gray-300 rounded-md text-sm">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $c)
                        <option value="{{ $c }}" @selected(request('category') === $c)>{{ $c }}</option>
                    @endforeach
                </select>
                <button class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">Filtrar</button>
            </form>
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr><th class="p-3">SKU</th><th class="p-3">Nombre</th><th class="p-3">Categoría</th><th class="p-3">Stock</th>
                        @if(auth()->user()->role === 'admin')<th class="p-3">Acciones</th>@endif</tr>
                    </thead>
                    <tbody>
                    @forelse($products as $p)
                        <tr class="border-t">
                            <td class="p-3">{{ $p->sku }}</td><td class="p-3">{{ $p->name }}</td>
                            <td class="p-3">{{ $p->category }}</td><td class="p-3 font-semibold">{{ $p->stock }}</td>
                            @if(auth()->user()->role === 'admin')
                            <td class="p-3">
                                <div class="flex gap-3">
                                    <a href="{{ route('products.edit', $p) }}" class="text-indigo-600">Editar</a>
                                    <form method="POST" action="{{ route('products.destroy', $p) }}" onsubmit="return confirm('¿Eliminar producto?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-4 text-center text-gray-500">Sin productos</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $products->links() }}</div>
        </div>
    </div>
</x-app-layout>