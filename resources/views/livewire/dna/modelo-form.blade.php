<div class="mx-auto max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ $modelo ? 'Editar bloco / peça' : 'Novo bloco / peça' }}
        </h1>
        <a href="{{ route('dna.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-primary-600">← Voltar</a>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Título *</label>
            <input type="text" wire:model="titulo" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            @error('titulo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                <select wire:model="categoria" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="banco_blocos">Banco de Blocos</option>
                    <option value="esqueleto">Esqueleto do Escritório</option>
                    <option value="regras">Regras Jurídicas</option>
                    <option value="rito">Rito & Estrutura</option>
                    <option value="sugestao">Sugestão</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Área</label>
                <select wire:model="area" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">—</option>
                    <option value="civel">Cível</option>
                    <option value="trabalhista">Trabalhista</option>
                    <option value="criminal">Criminal</option>
                    <option value="tributario">Tributário</option>
                    <option value="familia">Família</option>
                    <option value="previdenciario">Previdenciário</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de peça</label>
                <input type="text" wire:model="tipo_peca" placeholder="Petição inicial, contestação..." class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select wire:model="status" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="ativo">Ativo</option>
                    <option value="rascunho">Rascunho</option>
                    <option value="precisa_revisao">Precisa revisão</option>
                </select>
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Conteúdo</label>
            <textarea wire:model="conteudo" rows="16"
                      placeholder="Texto do bloco, esqueleto ou regra. Use {{ '{{cliente}}' }}, {{ '{{parte_contraria}}' }} etc. como marcadores para preencher depois."
                      class="w-full rounded-lg border-gray-300 bg-gray-50 font-mono text-sm leading-relaxed dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea>
            <p class="mt-1 text-xs text-gray-400">Editor de texto simples por enquanto — dá pra evoluir para rich text (negrito, listas) depois.</p>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('dna.index') }}" wire:navigate class="rounded-lg px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Cancelar</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-medium text-white hover:bg-primary-700">
                <span wire:loading.remove wire:target="save">Salvar</span>
                <span wire:loading wire:target="save">Salvando...</span>
            </button>
        </div>
    </form>
</div>
