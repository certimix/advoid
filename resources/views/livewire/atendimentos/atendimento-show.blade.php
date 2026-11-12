<div class="mx-auto max-w-3xl space-y-6">
    @if (session('ok'))
        <div class="rounded-lg bg-green-50 px-4 py-2 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-300">{{ session('ok') }}</div>
    @endif
    @if (session('erro'))
        <div class="rounded-lg bg-red-50 px-4 py-2 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-300">{{ session('erro') }}</div>
    @endif

    <div class="flex items-start justify-between">
        <div>
            <a href="{{ route('atendimentos.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-primary-600">← Atendimentos</a>
            <h1 class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $atendimento->assunto }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $atendimento->cliente?->nome ?? 'Sem cliente' }}
                @if ($atendimento->area) · {{ ucfirst($atendimento->area) }} @endif
            </p>
        </div>
        <a href="{{ route('atendimentos.edit', $atendimento) }}" wire:navigate class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Editar</a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <dl class="grid grid-cols-1 gap-x-8 gap-y-4 md:grid-cols-2">
            @foreach ([
                'Responsável' => $atendimento->responsavel?->name,
                'Tipo'        => $atendimento->tipo ? ucfirst($atendimento->tipo) : null,
                'Origem'      => $atendimento->origem ? ucfirst($atendimento->origem) : null,
                'Status'      => str_replace('_', ' ', ucfirst($atendimento->status)),
                'Aberto em'   => $atendimento->created_at?->format('d/m/Y H:i'),
            ] as $rotulo => $valor)
                <div>
                    <dt class="text-xs uppercase tracking-wide text-gray-400">{{ $rotulo }}</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ $valor ?: '—' }}</dd>
                </div>
            @endforeach
        </dl>

        @if ($atendimento->descricao)
            <div class="mt-6">
                <dt class="text-xs uppercase tracking-wide text-gray-400">Relato</dt>
                <dd class="mt-1 whitespace-pre-line text-sm text-gray-700 dark:text-gray-300">{{ $atendimento->descricao }}</dd>
            </div>
        @endif
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        @if ($atendimento->processo_id)
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600 dark:text-gray-300">Este atendimento já virou processo.</span>
                <a href="{{ route('processos.show', $atendimento->processo_id) }}" wire:navigate class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">Abrir processo</a>
            </div>
        @else
            <div class="flex flex-wrap items-center gap-3">
                <button wire:click="converterEmProcesso" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                    Converter em processo
                </button>
                @if ($atendimento->status !== 'encerrado')
                    <button wire:click="encerrar" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        Encerrar atendimento
                    </button>
                @endif
                <span class="text-xs text-gray-400">A conversão cria um processo com o cliente, área e relato deste atendimento.</span>
            </div>
        @endif
    </div>
</div>
