<div class="space-y-6">
    @if (session('ok'))
        <div class="rounded-lg bg-green-50 px-4 py-2 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-300">{{ session('ok') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Publicações</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Intimações e publicações dos diários, por processo.</p>
        </div>
        <div class="flex gap-2">
            @if ($naoLidas > 0)
                <button wire:click="marcarTodasLidas" class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Marcar todas lidas ({{ $naoLidas }})</button>
            @endif
            <a href="{{ route('publicacoes.create') }}" wire:navigate class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">+ Nova publicação</a>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        @foreach (['nao_lidas' => 'Não lidas', 'lidas' => 'Lidas', 'todas' => 'Todas'] as $v => $r)
            <button wire:click="$set('filtro', '{{ $v }}')"
                    class="rounded-lg px-3 py-1.5 text-sm font-medium transition {{ $filtro === $v ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">
                {{ $r }}
            </button>
        @endforeach
        <input type="text" wire:model.live.debounce.400ms="busca" placeholder="Buscar no conteúdo, CNJ, diário..."
               class="w-64 rounded-lg border-gray-300 bg-gray-50 text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
    </div>

    <div class="space-y-3">
        @forelse ($publicacoes as $pub)
            <div wire:key="pub-{{ $pub->id }}"
                 class="rounded-xl border bg-white p-4 dark:bg-gray-800 {{ $pub->lida ? 'border-gray-200 dark:border-gray-700' : 'border-primary-300 dark:border-primary-700' }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            @unless ($pub->lida) <span class="h-2 w-2 rounded-full bg-primary-500"></span> @endunless
                            <span>{{ $pub->data_publicacao?->format('d/m/Y') }}</span>
                            @if ($pub->diario) · <span>{{ $pub->diario }}</span> @endif
                            @if ($pub->processo) · <a href="{{ route('processos.show', $pub->processo) }}" wire:navigate class="text-primary-600 hover:underline">Proc. {{ $pub->processo->numero_processo ?: '#'.$pub->processo->id }}</a>
                            @elseif ($pub->numero_processo) · <span>{{ $pub->numero_processo }}</span> @endif
                        </div>
                        <p class="mt-2 whitespace-pre-line text-sm text-gray-800 dark:text-gray-200">{{ \Illuminate\Support\Str::limit($pub->conteudo, 400) }}</p>
                    </div>
                    <div class="flex shrink-0 flex-col items-end gap-2">
                        <button wire:click="toggleLida({{ $pub->id }})" class="rounded-lg border px-2.5 py-1 text-xs font-medium {{ $pub->lida ? 'border-gray-300 text-gray-500 dark:border-gray-600' : 'border-primary-500 text-primary-600' }}">
                            {{ $pub->lida ? 'Marcar não lida' : 'Marcar lida' }}
                        </button>
                        <a href="{{ route('publicacoes.edit', $pub) }}" wire:navigate class="text-xs text-gray-400 hover:text-primary-600">Editar</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-gray-300 p-12 text-center text-sm text-gray-500 dark:border-gray-600">
                Nenhuma publicação {{ $filtro === 'nao_lidas' ? 'não lida' : '' }}.
            </div>
        @endforelse
    </div>

    <div>{{ $publicacoes->links() }}</div>
</div>
