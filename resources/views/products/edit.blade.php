<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Editar producto</h2></x-slot>
    <div class="py-8">
        <div class="max-w-2xl mx-auto bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-4">
                @csrf @method('PUT')
                @include('products._form', ['product' => $product])
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-md">Actualizar</button>
                <a href="{{ route('products.index') }}" class="px-4 py-2 text-gray-600">Cancelar</a>
            </form>
        </div>
    </div>
</x-app-layout>