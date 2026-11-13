<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Clientes</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Pessoas físicas e jurídicas do escritório.</p>
        </div>
        <a href="{{ route('clientes.create') }}" wire:navigate
           class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-700">
            + Novo cliente
        </a>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <input type="text" wire:model.live.debounce.400ms="busca" placeholder="Buscar nome, CPF/CNPJ, e-mail..."
               class="w-72 rounded-lg border-gray-300 bg-gray-50 text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
        <select wire:model.live="tipo" class="rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            <option value="">Tipo (todos)</option>
            <option value="fisica">Pessoa física</option>
            <option value="juridica">Pessoa jurídica</option>
        </select>
        <select wire:model.live="ativo" class="rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            <option value="1">Ativos</option>
            <option value="0">Inativos</option>
            <option value="">Todos</option>
        </select>
        <button wire:click="limparFiltros" class="text-sm text-gray-500 hover:text-primary-600">✕ Limpar</button>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr class="text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">Nome</th>
                    <th class="px-4 py-3">CPF / CNPJ</th>
                    <th class="px-4 py-3">Contato</th>
                    <th class="px-4 py-3">Cidade/UF</th>
                    <th class="px-4 py-3 text-center">Processos</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                @forelse ($clientes as $cliente)
                    <tr wire:key="cli-{{ $cliente->id }}"
                        onclick="window.location='{{ route('clientes.show', $cliente) }}'"
                        class="cursor-pointer text-sm hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $cliente->nome }}</div>
                            <span class="text-xs text-gray-400">{{ $cliente->tipo === 'juridica' ? 'Pessoa jurídica' : 'Pessoa física' }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $cliente->cpf_cnpj ?: '—' }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                            <div>{{ $cliente->email ?: '—' }}</div>
                            <div class="text-xs text-gray-400">{{ $cliente->celular ?: $cliente->telefone }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $cliente->cidade }}{{ $cliente->uf ? '/'.$cliente->uf : '' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex min-w-[1.5rem] justify-center rounded-full bg-primary-50 px-2 py-0.5 text-xs font-medium text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ $cliente->processos_count }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-sm text-gray-500">Nenhum cliente encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $clientes->links() }}</div>
</div>
