<div class="mx-auto max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ $processo ? 'Editar processo' : 'Novo processo' }}
        </h1>
        <a href="{{ route('processos.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-primary-600">← Voltar</a>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente *</label>
                <select wire:model="cliente_id" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Selecione...</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                    @endforeach
                </select>
                @error('cliente_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Advogado responsável</label>
                <select wire:model="advogado_id" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Selecione...</option>
                    @foreach ($advogados as $adv)
                        <option value="{{ $adv->id }}">{{ $adv->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Número do processo (CNJ)</label>
                <input type="text" wire:model="numero_processo" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Pasta / nº interno</label>
                <input type="text" wire:model="numero_pasta" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de ação</label>
                <input type="text" wire:model="tipo_acao" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Área</label>
                <select wire:model="area" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Selecione...</option>
                    <option value="civel">Cível</option>
                    <option value="trabalhista">Trabalhista</option>
                    <option value="criminal">Criminal</option>
                    <option value="tributario">Tributário</option>
                    <option value="familia">Família</option>
                    <option value="previdenciario">Previdenciário</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Tribunal</label>
                <input type="text" wire:model="tribunal" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Comarca</label>
                <input type="text" wire:model="comarca" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Vara</label>
                <input type="text" wire:model="vara" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Instância</label>
                <select wire:model="instancia" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Selecione...</option>
                    <option value="1a">1ª Instância</option>
                    <option value="2a">2ª Instância</option>
                    <option value="superior">Tribunais Superiores</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Valor da causa (R$)</label>
                <input type="number" step="0.01" wire:model="valor_causa" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Data de distribuição</label>
                <input type="date" wire:model="data_distribuicao" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Fase</label>
                <select wire:model="fase" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="inicial">Inicial</option>
                    <option value="instrucao">Instrução</option>
                    <option value="recurso">Recurso</option>
                    <option value="execucao">Execução</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select wire:model="status" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="ativo">Ativo</option>
                    <option value="suspenso">Suspenso</option>
                    <option value="arquivado">Arquivado</option>
                    <option value="encerrado">Encerrado</option>
                </select>
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
            <textarea wire:model="observacoes" rows="3" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('processos.index') }}" wire:navigate class="rounded-lg px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Cancelar</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-medium text-white hover:bg-primary-700">
                <span wire:loading.remove wire:target="save">Salvar</span>
                <span wire:loading wire:target="save">Salvando...</span>
            </button>
        </div>
    </form>
</div>
