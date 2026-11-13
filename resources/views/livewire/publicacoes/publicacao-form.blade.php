<div class="mx-auto max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ $publicacao ? 'Editar publicação' : 'Nova publicação' }}
        </h1>
        <a href="{{ route('publicacoes.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-primary-600">← Voltar</a>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Data da publicação *</label>
                <input type="date" wire:model="data_publicacao" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                @error('data_publicacao') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Diário</label>
                <input type="text" wire:model="diario" placeholder="DJe, DOU..." class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Processo (vincular)</label>
                <select wire:model="processo_id" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Nenhum</option>
                    @foreach ($processos as $p)
                        <option value="{{ $p->id }}">{{ $p->numero_processo ?: 'Proc. #'.$p->id }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Nº do processo (texto)</label>
                <input type="text" wire:model="numero_processo" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Conteúdo *</label>
            <textarea wire:model="conteudo" rows="8" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea>
            @error('conteudo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
            <input type="checkbox" wire:model="lida" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
            Marcar como lida
        </label>

        <div class="flex justify-end gap-3">
            <a href="{{ route('publicacoes.index') }}" wire:navigate class="rounded-lg px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Cancelar</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-medium text-white hover:bg-primary-700">
                <span wire:loading.remove wire:target="save">Salvar</span>
                <span wire:loading wire:target="save">Salvando...</span>
            </button>
        </div>
    </form>
</div>
