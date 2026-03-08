@if($errors->any())
<div class="mb-4 rounded-xl bg-red-50 border border-red-200 p-4">
    <ul class="text-sm text-red-700 space-y-1">
        @foreach($errors->all() as $error)
        <li>• {{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="space-y-5">
    {{-- Título --}}
    <div>
        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
        <input type="text" id="titulo" name="titulo" required
               value="{{ old('titulo', $noticia->titulo ?? '') }}"
               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
               placeholder="Título de la noticia">
        @error('titulo')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Contenido (rich text editor) --}}
    <div x-data="richEditor()" x-init="init()">
        <label class="block text-sm font-medium text-gray-700 mb-1">Contenido <span class="text-red-500">*</span></label>
        <input type="hidden" name="contenido" x-model="content">

        {{-- Toolbar --}}
        <div class="flex flex-wrap items-center gap-1 border border-gray-300 rounded-t-xl bg-gray-50 px-2 py-1.5">
            <button type="button" @click="exec('bold')"
                    class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 font-bold text-sm w-8 h-8 flex items-center justify-center"
                    title="Negrita (Ctrl+B)"><b>B</b></button>
            <button type="button" @click="exec('italic')"
                    class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 italic text-sm w-8 h-8 flex items-center justify-center"
                    title="Cursiva (Ctrl+I)"><i>I</i></button>
            <button type="button" @click="exec('underline')"
                    class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 underline text-sm w-8 h-8 flex items-center justify-center"
                    title="Subrayado"><u>U</u></button>
            <div class="w-px h-5 bg-gray-300 mx-1"></div>
            <button type="button" @click="insertLink()"
                    class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 text-sm flex items-center gap-1 px-2 h-8"
                    title="Insertar enlace">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                Enlace
            </button>
            <div class="w-px h-5 bg-gray-300 mx-1"></div>
            <button type="button" @click="exec('insertUnorderedList')"
                    class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 text-sm w-8 h-8 flex items-center justify-center"
                    title="Lista">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <button type="button" @click="exec('formatBlock', 'h2')"
                    class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 text-xs font-bold w-8 h-8 flex items-center justify-center"
                    title="Encabezado">H2</button>
            <button type="button" @click="exec('formatBlock', 'p')"
                    class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 text-xs w-8 h-8 flex items-center justify-center"
                    title="Párrafo">¶</button>
        </div>

        {{-- Editor area --}}
        <div x-ref="editor"
             contenteditable="true"
             @input="syncContent()"
             @keydown.ctrl.b.prevent="exec('bold')"
             @keydown.ctrl.i.prevent="exec('italic')"
             class="min-h-[250px] p-4 border border-t-0 border-gray-300 rounded-b-xl focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 prose max-w-none text-sm text-gray-800 bg-white"
             style="line-height: 1.7;">
        </div>
        @error('contenido')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Meta description --}}
    <div>
        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta descripción (SEO)</label>
        <input type="text" id="meta_description" name="meta_description" maxlength="255"
               value="{{ old('meta_description', $noticia->meta_description ?? '') }}"
               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
               placeholder="Descripción breve para buscadores (máx. 255 caracteres)">
        <p class="text-xs text-gray-400 mt-1">Si se deja vacío, se usará el inicio del contenido.</p>
        @error('meta_description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Imagen --}}
    <div x-data="imagePreview()">
        <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
        @if(isset($noticia) && $noticia->imagen)
        <div class="mb-3">
            <img src="{{ Storage::url($noticia->imagen) }}" alt="Imagen actual"
                 class="h-40 object-cover rounded-xl border border-gray-200">
            <p class="text-xs text-gray-400 mt-1">Imagen actual. Sube una nueva para reemplazarla.</p>
        </div>
        @endif
        <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 transition"
               :class="preview ? 'border-indigo-400' : ''">
            <template x-if="preview">
                <img :src="preview" class="h-32 object-contain rounded-lg">
            </template>
            <template x-if="!preview">
                <div class="flex flex-col items-center gap-2 text-gray-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-sm">Haz clic para subir imagen</span>
                    <span class="text-xs">PNG, JPG, WebP — máx. 5 MB — se optimizará automáticamente</span>
                </div>
            </template>
            <input type="file" name="imagen" accept="image/*" class="sr-only" @change="onFileChange($event)">
        </label>
        @error('imagen')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Publicado --}}
    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
        <input type="checkbox" id="publicado" name="publicado" value="1"
               {{ old('publicado', ($noticia->publicado ?? false) ? '1' : '') === '1' ? 'checked' : '' }}
               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
        <label for="publicado" class="text-sm font-medium text-gray-700">
            Publicar inmediatamente
            <span class="font-normal text-gray-500">— visible en el sitio web y en la página de inicio</span>
        </label>
    </div>
</div>

@once
@push('scripts')
<script>
function richEditor() {
    return {
        content: @json(old('contenido', $noticia->contenido ?? '')),
        init() {
            if (this.content) {
                this.$refs.editor.innerHTML = this.content;
            }
        },
        exec(cmd, value = null) {
            this.$refs.editor.focus();
            document.execCommand(cmd, false, value);
            this.syncContent();
        },
        insertLink() {
            const url = prompt('Introduce la URL del enlace:');
            if (url) {
                this.$refs.editor.focus();
                document.execCommand('createLink', false, url);
                // Open in new tab
                const links = this.$refs.editor.querySelectorAll('a:not([target])');
                links.forEach(a => a.setAttribute('target', '_blank'));
                this.syncContent();
            }
        },
        syncContent() {
            this.content = this.$refs.editor.innerHTML;
        },
    };
}

function imagePreview() {
    return {
        preview: null,
        onFileChange(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (ev) => { this.preview = ev.target.result; };
                reader.readAsDataURL(file);
            }
        },
    };
}
</script>
@endpush
@endonce
