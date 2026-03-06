<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Editar Presupuesto <span class="text-gray-500">{{ $presupuesto->numero }}</span>
            </h2>
            <a href="{{ route('admin.presupuestos.show', $presupuesto->id) }}"
               class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <livewire:presupuestos.presupuesto-form :presupuesto-id="$presupuesto->id" />
        </div>
    </div>
</x-app-layout>
