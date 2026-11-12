<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ $atendimento ? 'Editar atendimento' : 'Novo atendimento' }}
        </h1>
        <a href="{{ route('atendimentos.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-primary-600">← Voltar</a>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Assunto *</label>
            <input type="text" wire:model="assunto" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            @error('assunto') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                <select wire:model="cliente_id" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Sem cliente (lead)</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                    @endforeach
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
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo</label>
                <select wire:model="tipo" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Selecione...</option>
                    <option value="consultivo">Consultivo</option>
                    <option value="contencioso">Contencioso</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Origem</label>
                <select wire:model="origem" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Selecione...</option>
                    <option value="whatsapp">WhatsApp</option>
                    <option value="indicacao">Indicação</option>
                    <option value="site">Site</option>
                    <option value="telefone">Telefone</option>
                    <option value="presencial">Presencial</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select wire:model="status" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="em_andamento">Em andamento</option>
                    <option value="triagem">Triagem</option>
                    <option value="virou_processo">Virou processo</option>
                    <option value="encerrado">Encerrado</option>
                </select>
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição / relato</label>
            <textarea wire:model="descricao" rows="4" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('atendimentos.index') }}" wire:navigate class="rounded-lg px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Cancelar</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-medium text-white hover:bg-primary-700">
                <span wire:loading.remove wire:target="save">Salvar</span>
                <span wire:loading wire:target="save">Salvando...</span>
            </button>
        </div>
    </form>
</div>
