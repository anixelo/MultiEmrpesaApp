{{-- Título --}}
<div>
    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
    <input type="text" id="titulo" name="titulo"
           value="{{ old('titulo', $categoria->titulo ?? '') }}"
           class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
           required>
    @error('titulo')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

{{-- Contenido --}}
<div>
    <label for="contenido" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
    <textarea id="contenido" name="contenido" rows="5"
              class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('contenido', $categoria->contenido ?? '') }}</textarea>
    @error('contenido')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

{{-- Imagen --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>

    @if(isset($categoria) && $categoria->imagen)
        <div class="mb-3">
            <img src="{{ Storage::url($categoria->imagen) }}"
                 alt="Imagen actual"
                 class="h-32 w-48 rounded-xl object-cover border border-gray-200">
            <p class="mt-1 text-xs text-gray-400">Imagen actual. Sube una nueva para reemplazarla.</p>
        </div>
    @endif

    <input type="file" name="imagen" accept="image/*"
           class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
    <p class="mt-1 text-xs text-gray-400">JPG, PNG o WebP. Máximo 5 MB. Se optimizará automáticamente.</p>

    @error('imagen')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
