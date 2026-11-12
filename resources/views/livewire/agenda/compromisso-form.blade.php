<div class="mx-auto max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ $compromisso ? 'Editar compromisso' : 'Novo compromisso' }}
        </h1>
        <a href="{{ route('agenda.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-primary-600">← Voltar</a>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Título *</label>
            <input type="text" wire:model="titulo" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            @error('titulo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo</label>
                <select wire:model="tipo" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="audiencia">Audiência</option>
                    <option value="julgamento">Julgamento</option>
                    <option value="pericia">Perícia</option>
                    <option value="reuniao">Reunião</option>
                    <option value="diligencia">Diligência</option>
                    <option value="tarefa">Tarefa</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Responsável</label>
                <select wire:model="responsavel_id" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Selecione...</option>
                    @foreach ($responsaveis as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Início *</label>
                <input type="datetime-local" wire:model="inicio" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                @error('inicio') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Fim</label>
                <input type="datetime-local" wire:model="fim" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                @error('fim') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Local</label>
                <input type="text" wire:model="local" placeholder="Fórum, sala, link..." class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Processo (opcional)</label>
                <select wire:model="processo_id" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Nenhum</option>
                    @foreach ($processos as $p)
                        <option value="{{ $p->id }}">{{ $p->numero_processo ?: 'Proc. #'.$p->id }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select wire:model="status" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="agendado">Agendado</option>
                    <option value="realizado">Realizado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
            <textarea wire:model="observacoes" rows="3" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('agenda.index') }}" wire:navigate class="rounded-lg px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Cancelar</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-medium text-white hover:bg-primary-700">
                <span wire:loading.remove wire:target="save">Salvar</span>
                <span wire:loading wire:target="save">Salvando...</span>
            </button>
        </div>
    </form>
</div>
