@foreach(['name' => 'Nombre', 'sku' => 'Código SKU', 'category' => 'Categoría'] as $campo => $label)
    <div>
        <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
        <input type="text" name="{{ $campo }}" value="{{ old($campo, $product->$campo ?? '') }}"
            class="mt-1 w-full border-gray-300 rounded-md" required>
        @error($campo)<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
@endforeach
<div>
    <label class="block text-sm font-medium text-gray-700">Descripción</label>
    <textarea name="description" rows="3" class="mt-1 w-full border-gray-300 rounded-md">{{ old('description', $product->description ?? '') }}</textarea>
</div>