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

{{-- Contenido (rich text editor) --}}
<div x-data="richEditorCat()" x-init="init()">
    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
    <input type="hidden" name="contenido" x-model="content">

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center gap-0.5 border border-gray-300 rounded-t-xl bg-gray-50 px-2 py-1.5">
        {{-- Undo / Redo --}}
        <button type="button" @click="exec('undo')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 w-8 h-8 flex items-center justify-center"
                title="Deshacer (Ctrl+Z)">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
        </button>
        <button type="button" @click="exec('redo')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 w-8 h-8 flex items-center justify-center"
                title="Rehacer (Ctrl+Y)">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 10H11a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"/></svg>
        </button>
        <div class="w-px h-5 bg-gray-300 mx-1"></div>
        {{-- Headings --}}
        <button type="button" @click="exec('formatBlock', 'h1')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 text-xs font-bold w-8 h-8 flex items-center justify-center"
                title="Título H1">H1</button>
        <button type="button" @click="exec('formatBlock', 'h2')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 text-xs font-bold w-8 h-8 flex items-center justify-center"
                title="Título H2">H2</button>
        <button type="button" @click="exec('formatBlock', 'h3')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 text-xs font-bold w-8 h-8 flex items-center justify-center"
                title="Título H3">H3</button>
        <button type="button" @click="exec('formatBlock', 'p')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 text-xs w-8 h-8 flex items-center justify-center"
                title="Párrafo">¶</button>
        <div class="w-px h-5 bg-gray-300 mx-1"></div>
        {{-- Text formatting --}}
        <button type="button" @click="exec('bold')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 font-bold text-sm w-8 h-8 flex items-center justify-center"
                title="Negrita (Ctrl+B)"><b>B</b></button>
        <button type="button" @click="exec('italic')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 italic text-sm w-8 h-8 flex items-center justify-center"
                title="Cursiva (Ctrl+I)"><i>I</i></button>
        <button type="button" @click="exec('underline')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 underline text-sm w-8 h-8 flex items-center justify-center"
                title="Subrayado (Ctrl+U)"><u>U</u></button>
        <button type="button" @click="exec('strikeThrough')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 line-through text-sm w-8 h-8 flex items-center justify-center"
                title="Tachado"><s>S</s></button>
        <div class="w-px h-5 bg-gray-300 mx-1"></div>
        {{-- Text alignment --}}
        <button type="button" @click="exec('justifyLeft')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 w-8 h-8 flex items-center justify-center"
                title="Alinear izquierda">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16"/></svg>
        </button>
        <button type="button" @click="exec('justifyCenter')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 w-8 h-8 flex items-center justify-center"
                title="Centrar">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M7 12h10M4 18h16"/></svg>
        </button>
        <button type="button" @click="exec('justifyRight')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 w-8 h-8 flex items-center justify-center"
                title="Alinear derecha">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M10 12h10M4 18h16"/></svg>
        </button>
        <button type="button" @click="exec('justifyFull')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 w-8 h-8 flex items-center justify-center"
                title="Justificar">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="w-px h-5 bg-gray-300 mx-1"></div>
        {{-- Lists --}}
        <button type="button" @click="exec('insertUnorderedList')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 w-8 h-8 flex items-center justify-center"
                title="Lista sin orden">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
        </button>
        <button type="button" @click="exec('insertOrderedList')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 w-8 h-8 flex items-center justify-center"
                title="Lista numerada">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </button>
        <button type="button" @click="exec('indent')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 w-8 h-8 flex items-center justify-center"
                title="Aumentar sangría">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h12M3 17h18M9 9l3 3-3 3"/></svg>
        </button>
        <button type="button" @click="exec('outdent')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 w-8 h-8 flex items-center justify-center"
                title="Reducir sangría">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7H3M21 12H9M21 17H3M15 9l-3 3 3 3"/></svg>
        </button>
        <div class="w-px h-5 bg-gray-300 mx-1"></div>
        {{-- Link & HR --}}
        <button type="button" @click="insertLink()"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 text-sm flex items-center gap-1 px-2 h-8"
                title="Insertar enlace">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            Enlace
        </button>
        <button type="button" @click="exec('insertHorizontalRule')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 text-xs flex items-center gap-1 px-2 h-8"
                title="Línea horizontal">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16"/></svg>
            HR
        </button>
        <div class="w-px h-5 bg-gray-300 mx-1"></div>
        {{-- Clear formatting --}}
        <button type="button" @click="exec('removeFormat')"
                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-500 text-xs flex items-center gap-1 px-2 h-8"
                title="Limpiar formato">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Limpiar
        </button>
        <div class="w-px h-5 bg-gray-300 mx-1"></div>
        {{-- HTML code view toggle --}}
        <button type="button" @click="toggleHtml()"
                :class="showHtml ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700'"
                class="p-1.5 rounded hover:bg-gray-200 transition text-xs flex items-center gap-1 px-2 h-8 font-mono font-bold"
                title="Ver/editar código HTML">
            &lt;/&gt;
        </button>
    </div>

    {{-- Editor area --}}
    <div x-ref="editor"
         x-show="!showHtml"
         contenteditable="true"
         @input="syncContent()"
         @keydown.ctrl.b.prevent="exec('bold')"
         @keydown.ctrl.i.prevent="exec('italic')"
         @keydown.ctrl.u.prevent="exec('underline')"
         @keydown.ctrl.z.prevent="exec('undo')"
         @keydown.ctrl.y.prevent="exec('redo')"
         class="min-h-[200px] p-4 border border-t-0 border-gray-300 rounded-b-xl focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 [&_ul]:list-disc [&_ol]:list-decimal [&_ul]:pl-5 [&_ol]:pl-5 [&_ul]:my-2 [&_ol]:my-2 max-w-none text-sm text-gray-800 bg-white"
         style="line-height: 1.7;">
    </div>
    {{-- HTML source code view --}}
    <textarea x-ref="htmlEditor"
              x-show="showHtml"
              x-model="content"
              @input="$refs.editor.innerHTML = content"
              class="min-h-[200px] w-full p-4 border border-t-0 border-gray-300 rounded-b-xl focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 text-xs text-gray-700 font-mono bg-gray-50"
              style="line-height: 1.7; resize: vertical;"></textarea>
    @error('contenido')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

@once
@push('scripts')
<script>
function richEditorCat() {
    return {
        content: @json(old('contenido', $categoria->contenido ?? '')),
        showHtml: false,
        init() {
            if (this.content) {
                this.$refs.editor.innerHTML = this.content;
            }
        },
        exec(cmd, value = null) {
            if (this.showHtml) return;
            this.$refs.editor.focus();
            document.execCommand(cmd, false, value);
            this.syncContent();
        },
        insertLink() {
            if (this.showHtml) return;
            const url = prompt('Introduce la URL del enlace:');
            if (url) {
                if (!/^https?:\/\//i.test(url)) {
                    alert('Por favor, introduce una URL válida (debe comenzar con http:// o https://).');
                    return;
                }
                this.$refs.editor.focus();
                document.execCommand('createLink', false, url);
                const links = this.$refs.editor.querySelectorAll('a:not([target])');
                links.forEach(a => a.setAttribute('target', '_blank'));
                this.syncContent();
            }
        },
        toggleHtml() {
            if (!this.showHtml) {
                this.syncContent();
                this.showHtml = true;
            } else {
                this.showHtml = false;
                this.$nextTick(() => {
                    this.$refs.editor.innerHTML = this.content;
                });
            }
        },
        syncContent() {
            this.content = this.$refs.editor.innerHTML;
        },
    };
}
</script>
@endpush
@endonce

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
