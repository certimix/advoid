<div class="space-y-6">
    @if (session('ok'))
        <div class="rounded-lg bg-green-50 px-4 py-2 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-300">{{ session('ok') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Financeiro</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Controle de receitas, despesas e cobranças.</p>
        </div>
        <a href="{{ route('financeiro.create') }}" wire:navigate
           class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-700">
            + Novo lançamento
        </a>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <div class="text-xs text-gray-500">Saldo</div>
            <div class="text-lg font-semibold {{ $saldo >= 0 ? 'text-primary-600' : 'text-red-600' }}">R$ {{ number_format($saldo, 2, ',', '.') }}</div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <div class="text-xs text-gray-500">Receitas</div>
            <div class="text-lg font-semibold text-green-600">R$ {{ number_format($receitas, 2, ',', '.') }}</div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <div class="text-xs text-gray-500">Despesas</div>
            <div class="text-lg font-semibold text-red-600">R$ {{ number_format($despesas, 2, ',', '.') }}</div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <div class="text-xs text-gray-500">Valor pago</div>
            <div class="text-lg font-semibold text-accent-600">R$ {{ number_format($pago, 2, ',', '.') }}</div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <div class="text-xs text-gray-500">Em atraso</div>
            <div class="text-lg font-semibold text-red-600">{{ $atraso }}</div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="flex flex-wrap items-center gap-2">
        @foreach ([
            'hoje'=>'Hoje','esta_semana'=>'Esta semana','este_mes'=>'Este mês',
            'mes_passado'=>'Mês passado','proximos_30'=>'Próximos 30d','vencidos'=>'Vencidos','todos'=>'Todos',
        ] as $p => $r)
            <button wire:click="$set('periodo', '{{ $p }}')"
                    class="rounded-lg px-3 py-1.5 text-sm font-medium transition {{ $periodo === $p ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">
                {{ $r }}
            </button>
        @endforeach
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <input type="text" wire:model.live.debounce.400ms="busca" placeholder="Buscar descrição..."
               class="w-56 rounded-lg border-gray-300 bg-gray-50 text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
        <select wire:model.live="tipo" class="rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            <option value="">Tipo (todos)</option>
            <option value="receita">Receita</option>
            <option value="despesa">Despesa</option>
            <option value="transferencia">Transferência</option>
        </select>
        <select wire:model.live="status" class="rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            <option value="">Status (todos)</option>
            <option value="pendente">Pendente</option>
            <option value="em_atraso">Em atraso</option>
            <option value="pago_parcial">Pago parcial</option>
            <option value="pago">Pago / Recebido</option>
            <option value="cancelado">Cancelado</option>
        </select>
        <button wire:click="limparFiltros" class="text-sm text-gray-500 hover:text-primary-600">✕ Limpar</button>
    </div>

    {{-- Tabela --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr class="text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">Tipo</th>
                    <th class="px-4 py-3">Descrição</th>
                    <th class="px-4 py-3">Cliente / Vínculo</th>
                    <th class="px-4 py-3">Vencimento</th>
                    <th class="px-4 py-3 text-right">Valor</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                @forelse ($lancamentos as $l)
                    @php
                        $atrasado = $l->status === 'pendente' && $l->vencimento && $l->vencimento->isPast();
                        $scores = [
                            'pendente'     => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
                            'pago'         => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                            'pago_parcial' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                            'cancelado'    => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                        ];
                    @endphp
                    <tr wire:key="l-{{ $l->id }}" class="text-sm hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <td class="px-4 py-3">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full {{ $l->tipo === 'receita' ? 'bg-green-100 text-green-600 dark:bg-green-900/40' : ($l->tipo === 'despesa' ? 'bg-red-100 text-red-600 dark:bg-red-900/40' : 'bg-gray-100 text-gray-600 dark:bg-gray-700') }}">
                                {{ $l->tipo === 'receita' ? '↓' : ($l->tipo === 'despesa' ? '↑' : '↔') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('financeiro.edit', $l) }}" wire:navigate class="font-medium text-gray-900 hover:text-primary-600 dark:text-white">{{ $l->descricao }}</a>
                            @if ($l->categoria) <div class="text-xs text-gray-400">{{ $l->categoria->nome }}</div> @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                            {{ $l->cliente?->nome ?? '—' }}
                            @if ($l->processo) <div class="text-xs text-gray-400">Proc. {{ $l->processo->numero_processo ?: '#'.$l->processo->id }}</div> @endif
                        </td>
                        <td class="px-4 py-3 {{ $atrasado ? 'font-medium text-red-600' : 'text-gray-600 dark:text-gray-300' }}">
                            {{ $l->vencimento?->format('d/m/Y') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-right font-semibold {{ $l->tipo === 'receita' ? 'text-green-600' : ($l->tipo === 'despesa' ? 'text-red-600' : 'text-gray-600') }}">
                            R$ {{ number_format($l->valor, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $atrasado ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300' : ($scores[$l->status] ?? 'bg-gray-100 text-gray-600') }}">
                                {{ $atrasado ? 'Em atraso' : str_replace('_', ' ', ucfirst($l->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if (! in_array($l->status, ['pago', 'cancelado']))
                                <button wire:click="marcarPago({{ $l->id }})" class="rounded-lg border border-green-500 px-2.5 py-1 text-xs font-medium text-green-600 hover:bg-green-50 dark:hover:bg-green-900/30">Baixar</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-12 text-center text-sm text-gray-500">Nenhum lançamento com esses filtros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $lancamentos->links() }}</div>
</div>
