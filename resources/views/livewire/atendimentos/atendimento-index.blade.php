<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Atendimentos</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Cada caso do cliente, da primeira conversa até virar processo.</p>
        </div>
        <a href="{{ route('atendimentos.create') }}" wire:navigate
           class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-700">
            + Novo atendimento
        </a>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach ([
            'em_andamento' => 'Em andamento', 'triagem' => 'Triagem',
            'virou_processo' => 'Virou processo', 'encerrado' => 'Encerrado', 'todos' => 'Todos',
        ] as $valor => $rotulo)
            <button wire:click="$set('status', '{{ $valor }}')"
                    class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-sm font-medium transition
                           {{ $status === $valor ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">
                {{ $rotulo }}
                <span class="rounded-full bg-black/10 px-1.5 text-xs">{{ $contadores[$valor] }}</span>
            </button>
        @endforeach
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <input type="text" wire:model.live.debounce.400ms="busca" placeholder="Buscar assunto, cliente..."
               class="w-64 rounded-lg border-gray-300 bg-gray-50 text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
        <select wire:model.live="area" class="rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            <option value="">Área (todas)</option>
            <option value="civel">Cível</option>
            <option value="trabalhista">Trabalhista</option>
            <option value="criminal">Criminal</option>
            <option value="tributario">Tributário</option>
            <option value="familia">Família</option>
            <option value="previdenciario">Previdenciário</option>
        </select>
        <select wire:model.live="tipo" class="rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            <option value="">Tipo (todos)</option>
            <option value="consultivo">Consultivo</option>
            <option value="contencioso">Contencioso</option>
        </select>
        <button wire:click="limparFiltros" class="text-sm text-gray-500 hover:text-primary-600">✕ Limpar filtros</button>
    </div>

    <div class="grid grid-cols-1 gap-3">
        @forelse ($atendimentos as $at)
            @php
                $cores = [
                    'em_andamento'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                    'triagem'        => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
                    'virou_processo' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                    'encerrado'      => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                ];
                $rotulos = ['em_andamento'=>'Em andamento','triagem'=>'Triagem','virou_processo'=>'Virou processo','encerrado'=>'Encerrado'];
            @endphp
            <a href="{{ route('atendimentos.show', $at) }}" wire:navigate wire:key="at-{{ $at->id }}"
               class="block rounded-xl border border-gray-200 bg-white p-4 transition hover:border-primary-300 hover:shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">{{ $at->assunto }}</div>
                        <div class="mt-0.5 text-xs text-gray-500">
                            {{ $at->cliente?->nome ?? 'Sem cliente' }}
                            @if ($at->area) · {{ ucfirst($at->area) }} @endif
                            @if ($at->origem) · {{ ucfirst($at->origem) }} @endif
                        </div>
                    </div>
                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $cores[$at->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $rotulos[$at->status] ?? $at->status }}
                    </span>
                </div>
                @if ($at->descricao)
                    <p class="mt-2 line-clamp-2 text-sm text-gray-600 dark:text-gray-400">{{ $at->descricao }}</p>
                @endif
                <div class="mt-2 text-xs text-gray-400">{{ $at->responsavel?->name }} · {{ $at->created_at?->format('d/m/Y') }}</div>
            </a>
        @empty
            <div class="rounded-xl border border-dashed border-gray-300 p-12 text-center text-sm text-gray-500 dark:border-gray-600">
                Nenhum atendimento com esses filtros.
            </div>
        @endforelse
    </div>

    <div>{{ $atendimentos->links() }}</div>
</div>
