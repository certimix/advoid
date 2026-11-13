<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Painel</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Visão geral do escritório.</p>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @php
            $cards = [
                ['Processos ativos', $processosAtivos, 'processos.index', 'text-primary-600', 'bg-primary-50 dark:bg-primary-900/30',
                 'M3.75 6.75A2.25 2.25 0 0 1 6 4.5h3.3a1.5 1.5 0 0 1 1.06.44l1.2 1.2a1.5 1.5 0 0 0 1.06.44H18a2.25 2.25 0 0 1 2.25 2.25v8.25A2.25 2.25 0 0 1 18 19.5H6a2.25 2.25 0 0 1-2.25-2.25Z'],
                ['Prazos pendentes', $prazosPendentes, null, 'text-red-600', 'bg-red-50 dark:bg-red-900/30',
                 'M12 6v6l3.75 2.25M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                ['Atendimentos abertos', $atendimentosAbertos, 'atendimentos.index', 'text-blue-600', 'bg-blue-50 dark:bg-blue-900/30',
                 'M4.5 5.25h15a1.5 1.5 0 0 1 1.5 1.5v8.25a1.5 1.5 0 0 1-1.5 1.5H9l-4.5 3.75V6.75a1.5 1.5 0 0 1 1.5-1.5Z'],
                ['Compromissos da semana', $compromissosSemana, 'agenda.index', 'text-accent-600', 'bg-accent-50 dark:bg-accent-900/30',
                 'M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6h15a1.5 1.5 0 0 1 1.5 1.5V19.5A1.5 1.5 0 0 1 19.5 21h-15A1.5 1.5 0 0 1 3 19.5V7.5A1.5 1.5 0 0 1 4.5 6Z'],
            ];
        @endphp
        @foreach ($cards as [$rotulo, $valor, $rota, $cor, $bg, $icone])
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-3xl font-bold {{ $cor }}">{{ $valor }}</div>
                        <div class="mt-1 text-sm text-gray-500">{{ $rotulo }}</div>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg {{ $bg }} {{ $cor }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icone }}" />
                        </svg>
                    </span>
                </div>
                @if ($rota)
                    <a href="{{ route($rota) }}" wire:navigate class="mt-3 inline-block text-xs font-medium {{ $cor }} hover:underline">Ver todos →</a>
                @endif
            </div>
        @endforeach
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <h2 class="mb-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Próximos compromissos</h2>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse ($proximos as $c)
                @php
                    $tcores = [
                        'audiencia' => 'bg-purple-500', 'julgamento' => 'bg-blue-500', 'pericia' => 'bg-amber-500',
                        'reuniao' => 'bg-teal-500', 'diligencia' => 'bg-pink-500', 'tarefa' => 'bg-gray-400',
                    ];
                @endphp
                <div class="flex items-center gap-3 py-3 text-sm">
                    <span class="h-2.5 w-2.5 rounded-full {{ $tcores[$c->tipo] ?? 'bg-gray-400' }}"></span>
                    <div class="flex-1">
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
