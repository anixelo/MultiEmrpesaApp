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
                    <h1 class="text-2xl font-bold text-slate-900">Nueva nota</h1>
                    <p class="mt-1 text-sm text-slate-500">Crea una nota y asígnala a un cliente</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Step indicator --}}
            <div class="mb-6">
                <ol class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @php
                        $steps = [1 => 'Cliente', 2 => 'Nota'];
                    @endphp

                    @foreach($steps as $num => $label)
                        @php
                            $completed = $step > $num;
                            $current   = $step === $num;
                            $upcoming  = $step < $num;
                        @endphp

                        <li>
                            <div class="flex items-center gap-3 rounded-3xl border p-4 shadow-sm
                                {{ $completed ? 'border-indigo-200 bg-indigo-50' : '' }}
                                {{ $current ? 'border-indigo-500 bg-white ring-2 ring-indigo-100 shadow-md' : '' }}
                                {{ $upcoming ? 'border-slate-200 bg-slate-50' : '' }}">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-bold
                                    {{ $completed ? 'bg-indigo-600 text-white' : '' }}
                                    {{ $current ? 'border-2 border-indigo-600 bg-white text-indigo-600' : '' }}
                                    {{ $upcoming ? 'border-2 border-slate-300 bg-white text-slate-400' : '' }}">
                                    @if($completed)
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        {{ $num }}
                                    @endif
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide {{ $upcoming ? 'text-slate-400' : 'text-indigo-600' }}">
                                        Paso {{ $num }}
                                    </p>
                                    <p class="text-sm font-semibold {{ $upcoming ? 'text-slate-500' : 'text-slate-900' }}">
                                        {{ $label }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                @if($step === 1)
                    {{-- STEP 1: Select Client --}}
                    <div x-data="clienteSelector()" class="space-y-5">

<script>
    function clienteSelector() {
        return {
            search: '',
            selected: null,
            selectedId: '',
            showNew: false,
            showModal: false,
            modalSearch: '',
            newNombre: '',
            newEmail: '',
            newTelefono: '',
            saving: false,
            clientes: @json($clientes),
            get filtered() {
                if (!this.search.trim()) return [];
                const q = this.search.toLowerCase();
                return this.clientes.filter(c =>
                    c.nombre.toLowerCase().includes(q) ||
                    (c.email && c.email.toLowerCase().includes(q))
                );
            },
            get modalFiltered() {
                if (!this.modalSearch.trim()) return this.clientes;
                const q = this.modalSearch.toLowerCase();
                return this.clientes.filter(c =>
                    c.nombre.toLowerCase().includes(q) ||
                    (c.email && c.email.toLowerCase().includes(q)) ||
                    (c.telefono && c.telefono.toLowerCase().includes(q))
                );
            },
            select(c) {
                this.selected = c;
                this.selectedId = c.id;
                this.search = c.nombre;
                this.showNew = false;
                this.showModal = false;
            },
            clear() {
                this.selected = null;
                this.selectedId = '';
                this.search = '';
            },
            openModal() {
                this.showModal = true;
                this.modalSearch = '';
                this.showNew = false;
            },
            closeModal() {
                this.showModal = false;
                this.modalSearch = '';
            },
            openInlineCreateForm() {
                this.showModal = false;
                this.showNew = true;
            },
            async saveNew() {
                if (!this.newNombre.trim()) return;
                this.saving = true;
                try {
                    const resp = await fetch('{{ route('admin.clientes.quick-store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({nombre: this.newNombre, email: this.newEmail, telefono: this.newTelefono})
                    });
                    if (!resp.ok) {
                        alert('Error al crear el cliente. Por favor, inténtalo de nuevo.');
                        return;
                    }
                    const data = await resp.json();
                    if (data && data.id && data.nombre) {
                        this.clientes.push(data);
                        this.select(data);
                        this.showNew = false;
                        this.newNombre = '';
                        this.newEmail = '';
                        this.newTelefono = '';
                    }
                } catch (e) {
                    alert('Error al crear el cliente. Por favor, inténtalo de nuevo.');
                } finally {
                    this.saving = false;
                }
            }
        }
    }
</script>

                        {{-- Client search --}}
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Cliente <span class="text-rose-500">*</span>
                            </label>

                            <template x-if="!selected">
                                <div class="relative">
                                    <div class="flex gap-2">
                                        <div class="relative flex-1">
                                            <input type="text" x-model="search"
                                                   class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                   placeholder="Buscar cliente por nombre o email...">

                                            <div x-show="search.trim().length > 0" class="absolute z-10 mt-2 max-h-48 w-full overflow-y-auto rounded-2xl border border-slate-200 bg-white shadow-xl">
                                                <template x-for="c in filtered" :key="c.id">
                                                    <button type="button" @click="select(c)"
                                                            class="w-full px-4 py-2.5 text-left text-sm transition hover:bg-indigo-50">
                                                        <span class="font-medium text-slate-900" x-text="c.nombre"></span>
                                                        <span class="text-slate-400" x-show="c.email" x-text="' — ' + c.email"></span>
                                                    </button>
                                                </template>

                                                <template x-if="search.trim().length > 0">
                                                    <div class="border-t border-slate-100">
                                                        <button type="button" @click="openInlineCreateForm()"
                                                                class="w-full px-4 py-2 text-left text-sm text-indigo-600 transition hover:bg-indigo-50">
                                                            + Crear nuevo cliente
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <button type="button" @click="openModal()"
                                                class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-3 py-2.5 text-slate-500 transition hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                title="Buscar en lista">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <template x-if="selected">
                                <div class="flex items-center gap-3 rounded-2xl border border-indigo-200 bg-indigo-50 px-4 py-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-slate-900" x-text="selected.nombre"></p>
                                        <p class="text-xs text-slate-500" x-show="selected.email" x-text="selected.email"></p>
                                    </div>
                                    <button type="button" @click="clear()"
                                            class="shrink-0 text-xs font-medium text-indigo-600 transition hover:underline">
                                        Cambiar
                                    </button>
                                </div>
                            </template>
                        </div>

                        {{-- Create new client inline --}}
                        <div x-show="showNew" x-transition>
                            <div class="space-y-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">Nuevo cliente</p>

                                <input type="text" x-model="newNombre" placeholder="Nombre *"
                                       class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                                <input type="email" x-model="newEmail" placeholder="Email"
                                       class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                                <input type="text" x-model="newTelefono" placeholder="Teléfono"
                                       class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                                <div class="flex gap-2 pt-1">
                                    <button type="button" @click="saveNew()" :disabled="saving || !newNombre.trim()"
                                            class="rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 disabled:opacity-50">
                                        <span x-text="saving ? 'Guardando...' : 'Guardar'"></span>
                                    </button>

                                    <button type="button" @click="showNew = false"
                                            class="rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Search modal --}}
                        <template x-if="showModal">
                            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="closeModal()">
                                <div class="w-full max-w-lg rounded-3xl bg-white shadow-2xl">
                                    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                                        <h3 class="text-lg font-semibold text-slate-900">Buscar cliente</h3>
                                        <button type="button" @click="closeModal()" class="text-slate-400 transition hover:text-slate-600">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="px-6 py-4">
                                        <input type="text" x-model="modalSearch"
                                               placeholder="Buscar por nombre, email o teléfono..."
                                               class="block w-full rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div class="max-h-72 overflow-y-auto border-t border-slate-100">
                                        <template x-if="modalFiltered.length > 0">
                                            <ul class="divide-y divide-slate-100">
                                                <template x-for="c in modalFiltered" :key="c.id">
                                                    <li>
                                                        <button type="button" @click="select(c)"
                                                                class="w-full px-6 py-3 text-left transition hover:bg-indigo-50">
                                                            <p class="text-sm font-medium text-slate-900" x-text="c.nombre"></p>
                                                            <p class="mt-0.5 text-xs text-slate-500" x-text="[c.email, c.telefono].filter(Boolean).join(' · ')"></p>
                                                        </button>
                                                    </li>
                                                </template>
                                            </ul>
                                        </template>

                                        <template x-if="modalFiltered.length === 0">
                                            <div class="px-6 py-4 text-sm text-slate-400">No se encontraron clientes.</div>
                                        </template>
                                    </div>

                                    <div class="flex items-center justify-between border-t border-slate-200 px-6 py-4">
                                        <button type="button" @click="openInlineCreateForm()"
                                                class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 transition hover:text-indigo-700">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Crear cliente
                                        </button>

                                        <button type="button" @click="closeModal()"
                                                class="inline-flex items-center rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-200">
                                            Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Proceed to step 2 --}}
                        <form method="GET" action="{{ route('admin.notas.create') }}" class="border-t border-slate-100 pt-2">
                            <input type="hidden" name="cliente_id" :value="selectedId">

                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.notas.index') }}"
                                   class="rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                                    Cancelar
                                </a>

                                <button type="submit" :disabled="!selectedId"
                                        class="rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 disabled:opacity-40">
                                    Siguiente →
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    {{-- STEP 2: Write Note --}}
                    {{-- Client info (read-only) --}}
                    <div class="mb-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="mb-0.5 text-xs font-semibold uppercase tracking-wide text-slate-500">Cliente</p>
                                <p class="font-semibold text-slate-900">{{ $cliente->nombre }}</p>
                                @if($cliente->email)
                                    <p class="mt-0.5 text-sm text-slate-500">{{ $cliente->email }}</p>
                                @endif
                                @if($cliente->telefono)
                                    <p class="text-sm text-slate-500">{{ $cliente->telefono }}</p>
                                @endif
                            </div>

                            <a href="{{ route('admin.notas.create') }}"
                               class="text-xs font-medium text-indigo-600 transition hover:underline">
                                Cambiar
                            </a>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.notas.store') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">

                        @if($errors->any())
                            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4">
                                <ul class="list-inside list-disc space-y-1 text-sm text-rose-700">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Título <span class="text-rose-500">*</span></label>
                            <input type="text" name="titulo" value="{{ old('titulo') }}" required
                                   class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('titulo') border-rose-400 @enderror"
                                   placeholder="Ej. Reunión inicial, Requisitos del proyecto...">
                            @error('titulo')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Contenido</label>
                            <textarea name="contenido" rows="8"
                                      class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Escribe aquí las notas...">{{ old('contenido') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-2">
                            <a href="{{ route('admin.notas.create') }}"
                               class="rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                                ← Atrás
                            </a>

                            <button type="submit"
                                    class="rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                                Guardar nota
                            </button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>