<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.notas.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Nueva Nota</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Step indicator --}}
            <div class="mb-6">
                <ol class="grid grid-cols-2 gap-4">
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
                        <div class="flex items-center gap-3 rounded-2xl border p-4 shadow-sm
                            {{ $completed ? 'border-indigo-200 bg-indigo-50' : '' }}
                            {{ $current   ? 'border-indigo-500 bg-white ring-2 ring-indigo-100 shadow-md' : '' }}
                            {{ $upcoming  ? 'border-gray-200 bg-gray-50' : '' }}">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold shrink-0
                                {{ $completed ? 'bg-indigo-600 text-white' : '' }}
                                {{ $current   ? 'border-2 border-indigo-600 text-indigo-600 bg-white' : '' }}
                                {{ $upcoming  ? 'border-2 border-gray-300 text-gray-400 bg-white' : '' }}">
                                @if($completed)
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                @else
                                    {{ $num }}
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide {{ $upcoming ? 'text-gray-400' : 'text-indigo-600' }}">Paso {{ $num }}</p>
                                <p class="text-sm font-semibold {{ $upcoming ? 'text-gray-500' : 'text-gray-900' }}">{{ $label }}</p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ol>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                @if($step === 1)
                {{-- STEP 1: Select Client --}}
                <div x-data="{
                    search: '',
                    selected: null,
                    selectedId: '',
                    showNew: false,
                    newNombre: '',
                    newEmail: '',
                    newTelefono: '',
                    saving: false,
                    clientes: @json($clientes),
                    get filtered() {
                        if (!this.search.trim()) return this.clientes;
                        const q = this.search.toLowerCase();
                        return this.clientes.filter(c =>
                            c.nombre.toLowerCase().includes(q) ||
                            (c.email && c.email.toLowerCase().includes(q))
                        );
                    },
                    select(c) {
                        this.selected = c;
                        this.selectedId = c.id;
                        this.search = c.nombre;
                        this.showNew = false;
                    },
                    clear() {
                        this.selected = null;
                        this.selectedId = '';
                        this.search = '';
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
                }" class="space-y-5">

                    {{-- Client search --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Cliente <span class="text-red-500">*</span>
                        </label>

                        <template x-if="!selected">
                            <div class="relative">
                                <input type="text" x-model="search"
                                       class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="Buscar cliente por nombre o email...">

                                <div x-show="search.trim().length > 0" class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg max-h-48 overflow-y-auto">
                                    <template x-for="c in filtered" :key="c.id">
                                        <button type="button" @click="select(c)"
                                                class="w-full text-left px-4 py-2.5 text-sm hover:bg-indigo-50 transition">
                                            <span class="font-medium text-gray-900" x-text="c.nombre"></span>
                                            <span class="text-gray-400" x-show="c.email" x-text="' — ' + c.email"></span>
                                        </button>
                                    </template>
                                    <template x-if="filtered.length === 0">
                                        <p class="px-4 py-2.5 text-sm text-gray-400">No se encontraron clientes.</p>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="selected">
                            <div class="flex items-center gap-3 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2.5">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900" x-text="selected.nombre"></p>
                                    <p class="text-xs text-gray-500" x-show="selected.email" x-text="selected.email"></p>
                                </div>
                                <button type="button" @click="clear()"
                                        class="text-xs text-indigo-600 hover:underline shrink-0">Cambiar</button>
                            </div>
                        </template>
                    </div>

                    {{-- Create new client --}}
                    <div>
                        <button type="button" @click="showNew = !showNew"
                                class="inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:underline">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Crear nuevo cliente
                        </button>

                        <div x-show="showNew" x-transition class="mt-3 rounded-xl border border-gray-200 bg-gray-50 p-4 space-y-3">
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Nuevo cliente</p>
                            <input type="text" x-model="newNombre" placeholder="Nombre *"
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <input type="email" x-model="newEmail" placeholder="Email"
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <input type="text" x-model="newTelefono" placeholder="Teléfono"
                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <div class="flex gap-2 pt-1">
                                <button type="button" @click="saveNew()" :disabled="saving || !newNombre.trim()"
                                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition disabled:opacity-50">
                                    <span x-text="saving ? 'Guardando...' : 'Guardar'"></span>
                                </button>
                                <button type="button" @click="showNew = false"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Proceed to step 2 --}}
                    <form method="GET" action="{{ route('admin.notas.create') }}" class="pt-2 border-t border-gray-100">
                        <input type="hidden" name="cliente_id" :value="selectedId">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.notas.index') }}"
                               class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                                Cancelar
                            </a>
                            <button type="submit" :disabled="!selectedId"
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-sm disabled:opacity-40">
                                Siguiente →
                            </button>
                        </div>
                    </form>
                </div>

                @else
                {{-- STEP 2: Write Note --}}
                {{-- Client info (read-only) --}}
                <div class="mb-5 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-0.5">Cliente</p>
                            <p class="font-semibold text-gray-900">{{ $cliente->nombre }}</p>
                            @if($cliente->email)
                            <p class="text-sm text-gray-500 mt-0.5">{{ $cliente->email }}</p>
                            @endif
                            @if($cliente->telefono)
                            <p class="text-sm text-gray-500">{{ $cliente->telefono }}</p>
                            @endif
                        </div>
                        <a href="{{ route('admin.notas.create') }}"
                           class="text-xs text-indigo-600 hover:underline">Cambiar</a>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.notas.store') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">

                    @if($errors->any())
                    <div class="rounded-md bg-red-50 p-4">
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                        <input type="text" name="titulo" value="{{ old('titulo') }}" required
                               class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('titulo') border-red-400 @enderror"
                               placeholder="Ej. Reunión inicial, Requisitos del proyecto...">
                        @error('titulo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
                        <textarea name="contenido" rows="8"
                                  class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Escribe aquí las notas...">{{ old('contenido') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('admin.notas.create') }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                            ← Atrás
                        </a>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-sm">
                            Guardar Nota
                        </button>
                    </div>
                </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
