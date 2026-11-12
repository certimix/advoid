<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Gestão de Processos</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Gerencie seus processos, audiências e prazos.</p>
        </div>
        <a href="{{ route('processos.create') }}" wire:navigate
           class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-700">
            + Novo Processo
        </a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
            <div class="md:col-span-2">
                <input type="text" wire:model.live.debounce.400ms="busca"
                       placeholder="Buscar por CNJ, pasta, parte ou cliente..."
                       class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <select wire:model.live="area" class="rounded-lg border-gray-300 bg-gray-50 text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                <option value="">Área (todas)</option>
                <option value="civel">Cível</option>
                <option value="trabalhista">Trabalhista</option>
                <option value="criminal">Criminal</option>
                <option value="tributario">Tributário</option>
                <option value="familia">Família</option>
                <option value="previdenciario">Previdenciário</option>
            </select>
            <select wire:model.live="instancia" class="rounded-lg border-gray-300 bg-gray-50 text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                <option value="">Instância (todas)</option>
                <option value="1a">1ª Instância</option>
                <option value="2a">2ª Instância</option>
                <option value="superior">Tribunais Superiores</option>
            </select>
        </div>
        <div class="mt-3 flex items-center gap-3">
            <input type="text" wire:model.live.debounce.400ms="tribunal" placeholder="Tribunal..."
                   class="w-48 rounded-lg border-gray-300 bg-gray-50 text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            <button wire:click="limparFiltros" class="text-sm text-gray-500 hover:text-primary-600">✕ Limpar filtros</button>
        </div>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach (['todos' => 'Todos', 'ativo' => 'Ativos', 'arquivado' => 'Arquivados', 'suspenso' => 'Suspensos'] as $valor => $rotulo)
            <button wire:click="$set('status', '{{ $valor }}')"
                    class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-sm font-medium transition
                           {{ $status === $valor ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">
                <span class="rounded-full bg-black/10 px-1.5 text-xs">{{ $contadores[$valor] }}</span>
                {{ $rotulo }}
            </button>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr class="text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">Processo / Pasta</th>
                    <th class="px-4 py-3">Cliente</th>
                    <th class="px-4 py-3">Área</th>
                    <th class="px-4 py-3">Tribunal</th>
                    <th class="px-4 py-3">Responsável</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                @forelse ($processos as $processo)
                    <tr wire:key="proc-{{ $processo->id }}"
                        onclick="window.location='{{ route('processos.show', $processo) }}'"
                        class="cursor-pointer text-sm hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $processo->numero_processo ?: '— sem número —' }}</div>
                            <div class="text-xs text-gray-500">{{ $processo->numero_pasta }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $processo->cliente?->nome }}</td>
                        <td class="px-4 py-3 capitalize text-gray-700 dark:text-gray-300">{{ $processo->area }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $processo->tribunal }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $processo->advogado?->name }}</td>
                        <td class="px-4 py-3">
                            @php
                                $cores = [
                                    'ativo'     => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                                    'suspenso'  => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
                                    'arquivado' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                    'encerrado' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                                ];
                            @endphp
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium capitalize {{ $cores[$processo->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $processo->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-500">
                            Nenhum processo encontrado. Ajuste os filtros ou crie um novo.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $processos->links() }}</div>
</div>
