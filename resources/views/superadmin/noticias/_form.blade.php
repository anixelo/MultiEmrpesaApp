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
             class="min-h-[300px] p-4 border border-t-0 border-gray-300 rounded-b-xl focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 [&_ul]:list-disc [&_ol]:list-decimal [&_ul]:pl-5 [&_ol]:pl-5 [&_ul]:my-2 [&_ol]:my-2 max-w-none text-sm text-gray-800 bg-white"
             style="line-height: 1.7;">
        </div>
        {{-- HTML source code view --}}
        <textarea x-ref="htmlEditor"
                  x-show="showHtml"
                  x-model="content"
                  @input="$refs.editor.innerHTML = content"
                  class="min-h-[300px] w-full p-4 border border-t-0 border-gray-300 rounded-b-xl focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 text-xs text-gray-700 font-mono bg-gray-50"
                  style="line-height: 1.7; resize: vertical;"></textarea>
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

    {{-- Tags --}}
    <div x-data="tagInput({{ json_encode(collect(old('tags', isset($noticia) ? $noticia->tags->pluck('nombre')->toArray() : []))->values()->all()) }})">
        <label class="block text-sm font-medium text-gray-700 mb-1">Etiquetas</label>
        <div class="flex flex-wrap gap-1.5 p-2 border border-gray-300 rounded-xl bg-white min-h-[44px] cursor-text"
             @click="$refs.tagInput.focus()">
            <template x-for="(tag, i) in tags" :key="i">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                    <input type="hidden" :name="'tags[' + i + ']'" :value="tag">
                    <span x-text="tag"></span>
                    <button type="button" @click.stop="removeTag(i)" class="text-indigo-400 hover:text-indigo-700 ml-0.5 flex items-center">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </span>
            </template>
            <input x-ref="tagInput"
                   type="text"
                   placeholder="Añadir etiqueta…"
                   class="flex-1 min-w-[140px] border-0 focus:ring-0 text-sm text-gray-700 p-0.5 bg-transparent"
                   @keydown.enter.prevent="addFromInput()"
                   @keydown.comma.prevent="addFromInput()"
                   @keydown.backspace="onBackspace($event)">
        </div>
        <p class="text-xs text-gray-400 mt-1">Escribe una etiqueta y pulsa Enter o coma para añadirla. Puedes crear etiquetas nuevas.</p>

        @if(isset($tags) && $tags->isNotEmpty())
        <div class="mt-2 flex flex-wrap gap-1.5">
            <span class="text-xs text-gray-400 self-center">Etiquetas existentes:</span>
            @foreach($tags as $t)
            <button type="button"
                    @click="toggleExisting('{{ $t->nombre }}')"
                    :class="tags.includes('{{ $t->nombre }}') ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-indigo-50'"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border transition-colors">
                {{ $t->nombre }}
            </button>
            @endforeach
        </div>
        @endif
    </div>
</div>

@once
@push('scripts')
<script>
function richEditor() {
    return {
        content: @json(old('contenido', $noticia->contenido ?? '')),
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
                this.$refs.editor.focus();
                document.execCommand('createLink', false, url);
                // Open in new tab
                const links = this.$refs.editor.querySelectorAll('a:not([target])');
                links.forEach(a => a.setAttribute('target', '_blank'));
                this.syncContent();
            }
        },
        toggleHtml() {
            if (!this.showHtml) {
                // Switching to HTML view: sync textarea with current editor content
                this.syncContent();
            } else {
                // Switching back to WYSIWYG: load editor from textarea content
                this.$nextTick(() => {
                    this.$refs.editor.innerHTML = this.content;
                });
            }
            this.showHtml = !this.showHtml;
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

function tagInput(initialTags) {
    return {
        tags: initialTags || [],
        addFromInput() {
            const val = this.$refs.tagInput.value.trim().replace(/,$/, '').trim();
            if (val && !this.tags.includes(val)) {
                this.tags.push(val);
            }
            this.$refs.tagInput.value = '';
        },
        removeTag(index) {
            this.tags.splice(index, 1);
        },
        toggleExisting(nombre) {
            if (this.tags.includes(nombre)) {
                this.tags = this.tags.filter(t => t !== nombre);
            } else {
                this.tags.push(nombre);
            }
        },
        onBackspace(e) {
            if (e.target.value === '' && this.tags.length > 0) {
                this.tags.pop();
            }
        },
    };
}
</script>
@endpush
@endonce
