<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Painel</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Visão geral do escritório.</p>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ([
            ['Processos ativos', $processosAtivos, 'processos.index'],
            ['Prazos pendentes', $prazosPendentes, null],
            ['Atendimentos abertos', $atendimentosAbertos, 'atendimentos.index'],
            ['Compromissos da semana', $compromissosSemana, 'agenda.index'],
        ] as [$rotulo, $valor, $rota])
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                <div class="text-3xl font-bold text-primary-600">{{ $valor }}</div>
                <div class="mt-1 text-sm text-gray-500">{{ $rotulo }}</div>
                @if ($rota)
                    <a href="{{ route($rota) }}" wire:navigate class="mt-2 inline-block text-xs text-primary-600 hover:underline">Ver →</a>
                @endif
            </div>
        @endforeach
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <h2 class="mb-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Próximos compromissos</h2>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse ($proximos as $c)
                <div class="flex items-center justify-between py-3 text-sm">
                    <div>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $c->titulo }}</span>
                        <span class="ml-2 text-xs capitalize text-gray-400">{{ $c->tipo }}</span>
                    </div>
                    <span class="text-xs text-gray-500">{{ $c->inicio->format('d/m/Y H:i') }}</span>
                </div>
            @empty
                <p class="py-3 text-sm text-gray-500">Nenhum compromisso agendado.</p>
            @endforelse
        </div>
    </div>
</div>
