<div class="space-y-6">
    @if (session('ok'))
        <div class="rounded-lg bg-green-50 px-4 py-2 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-300">{{ session('ok') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">DNA Jurídico</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Blocos e peças que definem o estilo e a argumentação do escritório.</p>
        </div>
        <a href="{{ route('dna.create') }}" wire:navigate
           class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-700">
            + Novo bloco
        </a>
    </div>

    {{-- Abas de categoria --}}
    <div class="flex flex-wrap gap-2">
        @foreach ([
            '' => 'Todos', 'banco_blocos' => 'Banco de Blocos', 'esqueleto' => 'Esqueleto do Escritório',
            'regras' => 'Regras Jurídicas', 'rito' => 'Rito & Estrutura', 'sugestao' => 'Sugestões',
        ] as $valor => $rotulo)
            <button wire:click="$set('categoria', '{{ $valor }}')"
                    class="rounded-lg px-3 py-1.5 text-sm font-medium transition
                           {{ $categoria === $valor ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">
                {{ $rotulo }}
            </button>
        @endforeach
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <input type="text" wire:model.live.debounce.400ms="busca" placeholder="Buscar bloco/peça..."
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
        <select wire:model.live="status" class="rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            <option value="">Status (todos)</option>
            <option value="ativo">Ativo</option>
            <option value="rascunho">Rascunho</option>
            <option value="precisa_revisao">Precisa revisão</option>
        </select>
        <button wire:click="limparFiltros" class="text-sm text-gray-500 hover:text-primary-600">✕ Limpar</button>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($modelos as $m)
            @php
                $catrot = [
                    'banco_blocos' => 'Banco de Blocos', 'esqueleto' => 'Esqueleto',
                    'regras' => 'Regras', 'rito' => 'Rito & Estrutura', 'sugestao' => 'Sugestão',
                ];
                $strot = ['ativo' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                          'rascunho' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                          'precisa_revisao' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300'];
            @endphp
            <div wire:key="dna-{{ $m->id }}" class="flex flex-col rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-start justify-between">
                    <span class="inline-flex rounded bg-primary-50 px-2 py-0.5 text-[10px] font-medium text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ $catrot[$m->categoria] ?? $m->categoria }}</span>
                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-medium {{ $strot[$m->status] ?? 'bg-gray-100 text-gray-600' }}">{{ str_replace('_',' ',$m->status) }}</span>
                </div>
                <a href="{{ route('dna.edit', $m) }}" wire:navigate class="mt-2 font-medium text-gray-900 hover:text-primary-600 dark:text-white">{{ $m->titulo }}</a>
                <div class="mt-1 text-xs text-gray-400">
                    @if ($m->area) {{ ucfirst($m->area) }} @endif
                    @if ($m->tipo_peca) · {{ $m->tipo_peca }} @endif
                </div>
                @if ($m->conteudo)
                    <p class="mt-2 line-clamp-3 flex-1 text-xs text-gray-500 dark:text-gray-400">{{ \Illuminate\Support\Str::limit(strip_tags($m->conteudo), 160) }}</p>
                @endif
                <div class="mt-3 flex items-center justify-end gap-3 border-t border-gray-100 pt-2 dark:border-gray-700">
                    <a href="{{ route('dna.edit', $m) }}" wire:navigate class="text-xs text-primary-600 hover:underline">Editar</a>
                    <button wire:click="excluir({{ $m->id }})" wire:confirm="Excluir este bloco?" class="text-xs text-red-500 hover:underline">Excluir</button>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-xl border border-dashed border-gray-300 p-12 text-center text-sm text-gray-500 dark:border-gray-600">
                Nenhum bloco/peça cadastrado ainda.
            </div>
        @endforelse
    </div>

    <div>{{ $modelos->links() }}</div>
</div>
