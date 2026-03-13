<x-app-layout>


    <x-slot name="header">
        <div class="flex items-start gap-3 sm:items-center">
            <a href="{{ route('admin.notas.index') }}"
               class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:bg-slate-50 hover:text-slate-600 sm:mt-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div class="min-w-0">
                <h1 class="text-2xl font-bold text-slate-900">Editar nota</h1>
                <p class="mt-1 truncate text-sm text-slate-500">{{$nota->titulo }}</p>
            </div>
        </div>
    </x-slot>


    <div class="py-6">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <form method="POST" action="{{ route('admin.notas.update', $nota->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4">
                            <ul class="list-inside list-disc space-y-1 text-sm text-rose-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Client section --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Datos del cliente</h3>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Cliente <span class="text-rose-500">*</span></label>
                            <select name="cliente_id" required
                                    class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('cliente_id') border-rose-400 @enderror">
                                <option value="">— Selecciona un cliente —</option>
                                @foreach($clientes as $c)
                                    <option value="{{ $c->id }}" {{ old('cliente_id', $nota->cliente_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->nombre }}{{ $c->email ? ' — ' . $c->email : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Note section --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Nota</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Título <span class="text-rose-500">*</span></label>
                                <input type="text" name="titulo" value="{{ old('titulo', $nota->titulo) }}" required
                                       class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('titulo') border-rose-400 @enderror">
                                @error('titulo')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-data="notaRichEditor()" x-init="init()">
                                <label class="mb-1 block text-sm font-medium text-slate-700">Contenido</label>
                                <input type="hidden" name="contenido" x-model="content">

                                {{-- Toolbar --}}
                                <div class="flex flex-wrap items-center gap-0.5 border border-slate-300 rounded-t-2xl bg-slate-50 px-2 py-1.5">
                                    <button type="button" @click="exec('undo')" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-700 w-8 h-8 flex items-center justify-center" title="Deshacer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                    </button>
                                    <button type="button" @click="exec('redo')" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-700 w-8 h-8 flex items-center justify-center" title="Rehacer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 10H11a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"/></svg>
                                    </button>
                                    <div class="w-px h-5 bg-slate-300 mx-1"></div>
                                    <button type="button" @click="exec('bold')" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-700 font-bold text-sm w-8 h-8 flex items-center justify-center" title="Negrita"><b>B</b></button>
                                    <button type="button" @click="exec('italic')" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-700 italic text-sm w-8 h-8 flex items-center justify-center" title="Cursiva"><i>I</i></button>
                                    <button type="button" @click="exec('underline')" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-700 underline text-sm w-8 h-8 flex items-center justify-center" title="Subrayado"><u>U</u></button>
                                    <div class="w-px h-5 bg-slate-300 mx-1"></div>
                                    <button type="button" @click="exec('insertUnorderedList')" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-700 w-8 h-8 flex items-center justify-center" title="Lista">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                    </button>
                                    <button type="button" @click="exec('insertOrderedList')" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-700 w-8 h-8 flex items-center justify-center" title="Lista numerada">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    </button>
                                    <div class="w-px h-5 bg-slate-300 mx-1"></div>
                                    <button type="button" @click="insertLink()" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-700 text-xs flex items-center gap-1 px-2 h-8" title="Insertar enlace">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                        Enlace
                                    </button>
                                    <div class="w-px h-5 bg-slate-300 mx-1"></div>
                                    <button type="button" @click="exec('removeFormat')" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-500 text-xs flex items-center gap-1 px-2 h-8" title="Limpiar formato">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Limpiar
                                    </button>
                                </div>

                                {{-- Editor area --}}
                                <div x-ref="editor"
                                     contenteditable="true"
                                     @input="syncContent()"
                                     @keydown.ctrl.b.prevent="exec('bold')"
                                     @keydown.ctrl.i.prevent="exec('italic')"
                                     @keydown.ctrl.u.prevent="exec('underline')"
                                     @keydown.ctrl.z.prevent="exec('undo')"
                                     @keydown.ctrl.y.prevent="exec('redo')"
                                     class="min-h-[200px] p-4 border border-t-0 border-slate-300 rounded-b-2xl focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 [&_ul]:list-disc [&_ol]:list-decimal [&_ul]:pl-5 [&_ol]:pl-5 text-sm text-slate-800 bg-white"
                                     style="line-height: 1.7;">
                                </div>
                            </div>

                            <script>
                            function notaRichEditor() {
                                return {
                                    content: @json(old('contenido', $nota->contenido ?? '')),
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
                            </script>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-2">
                        <a href="{{ route('admin.notas.show', $nota->id) }}"
                           class="rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                            Cancelar
                        </a>

                        <button type="submit"
                                class="rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                            Guardar cambios
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>