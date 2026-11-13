<div class="mx-auto max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $equipe ? 'Editar equipe' : 'Nova equipe' }}</h1>
        <a href="{{ route('equipes.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-primary-600">← Voltar</a>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Nome *</label>
            <input type="text" wire:model="nome" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            @error('nome') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
            <textarea wire:model="descricao" rows="2" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea>
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Membros</label>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                @foreach ($usuarios as $u)
                    <label class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm dark:border-gray-700">
                        <input type="checkbox" value="{{ $u->id }}" wire:model="membros" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-gray-800 dark:text-gray-200">{{ $u->name }}</span>
                        <span class="ml-auto text-xs text-gray-400 capitalize">{{ $u->cargo }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('equipes.index') }}" wire:navigate class="rounded-lg px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Cancelar</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-medium text-white hover:bg-primary-700">
                <span wire:loading.remove wire:target="save">Salvar</span>
                <span wire:loading wire:target="save">Salvando...</span>
            </button>
        </div>
    </form>
</div>
