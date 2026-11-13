<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ $cliente ? 'Editar cliente' : 'Novo cliente' }}
        </h1>
        <a href="{{ route('clientes.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-primary-600">← Voltar</a>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo</label>
            <div class="grid max-w-md grid-cols-2 gap-3">
                @foreach (['fisica'=>'Pessoa física','juridica'=>'Pessoa jurídica'] as $v => $r)
                    <button type="button" wire:click="$set('tipo', '{{ $v }}')"
                            class="rounded-lg border px-3 py-2 text-sm font-medium transition
                                   {{ $tipo === $v ? 'border-primary-500 bg-primary-50 text-primary-700 dark:bg-primary-900/20 dark:text-primary-300' : 'border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        {{ $r }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $tipo === 'juridica' ? 'Razão social *' : 'Nome completo *' }}</label>
                <input type="text" wire:model="nome" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                @error('nome') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $tipo === 'juridica' ? 'CNPJ' : 'CPF' }}</label>
                <input type="text" wire:model="cpf_cnpj" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $tipo === 'juridica' ? 'Inscrição estadual' : 'RG' }}</label>
                <input type="text" wire:model="rg" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">E-mail</label>
                <input type="email" wire:model="email" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone</label>
                    <input type="text" wire:model="telefone" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Celular</label>
                    <input type="text" wire:model="celular" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4 dark:border-gray-700">
            <h3 class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Endereço</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-6">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-xs text-gray-500">CEP</label>
                    <input type="text" wire:model="cep" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
                <div class="md:col-span-3">
                    <label class="mb-1 block text-xs text-gray-500">Logradouro</label>
                    <input type="text" wire:model="logradouro" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-xs text-gray-500">Número</label>
                    <input type="text" wire:model="numero" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-xs text-gray-500">Complemento</label>
                    <input type="text" wire:model="complemento" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-xs text-gray-500">Bairro</label>
                    <input type="text" wire:model="bairro" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-xs text-gray-500">Cidade</label>
                    <input type="text" wire:model="cidade" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-xs text-gray-500">UF</label>
                    <input type="text" maxlength="2" wire:model="uf" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm uppercase dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
            <textarea wire:model="observacoes" rows="3" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea>
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
            <input type="checkbox" wire:model="ativo" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
            Cliente ativo
        </label>

        <div class="flex justify-end gap-3">
            <a href="{{ route('clientes.index') }}" wire:navigate class="rounded-lg px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Cancelar</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-medium text-white hover:bg-primary-700">
                <span wire:loading.remove wire:target="save">Salvar</span>
                <span wire:loading wire:target="save">Salvando...</span>
            </button>
        </div>
    </form>
</div>
