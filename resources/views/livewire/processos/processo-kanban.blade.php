<div class="space-y-6" x-data="{ dragId: null }">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Kanban de Processos</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Arraste os cards entre as fases.</p>
        </div>
        <a href="{{ route('processos.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-primary-600">Ver em lista →</a>
    </div>

    {{-- Abas de quadro --}}
    <div class="flex gap-2">
        @foreach (['judicial' => 'Judicial', 'administrativo' => 'Administrativo', 'arquivamento' => 'Arquivamento'] as $q => $r)
            <button wire:click="$set('quadro', '{{ $q }}')"
                    class="border-b-2 px-3 py-2 text-sm font-medium transition
                           {{ $quadro === $q ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                {{ $r }}
            </button>
        @endforeach
    </div>

    @if ($fases->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-600">
            <p class="text-sm text-gray-500">Nenhuma fase cadastrada neste quadro.</p>
            <button wire:click="criarFasesPadrao" class="mt-3 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">
                Criar fases padrão
            </button>
        </div>
    @endif

    {{-- Board --}}
    <div class="flex gap-4 overflow-x-auto pb-4">
        {{-- Coluna Sem fase --}}
        <div class="w-72 shrink-0"
             @dragover.prevent
             @drop="$wire.mover(dragId, null); dragId = null">
            <div class="mb-2 flex items-center justify-between px-1">
                <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-300">Sem fase</h2>
                <span class="text-xs text-gray-400">{{ $semFase->count() }}</span>
            </div>
            <div class="min-h-[8rem] space-y-2 rounded-xl bg-gray-100/60 p-2 dark:bg-gray-800/40">
                @forelse ($semFase as $p)
                    @include('livewire.processos.partials.kanban-card', ['p' => $p])
                @empty
                    <p class="px-2 py-6 text-center text-xs text-gray-400">—</p>
                @endforelse
            </div>
        </div>

        {{-- Colunas por fase --}}
        @foreach ($fases as $fase)
            <div class="w-72 shrink-0"
                 @dragover.prevent
                 @drop="$wire.mover(dragId, {{ $fase->id }}); dragId = null">
                <div class="mb-2 flex items-center justify-between px-1">
                    <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $fase->nome }}</h2>
                    <span class="text-xs text-gray-400">{{ ($porFase[$fase->id] ?? collect())->count() }}</span>
                </div>
                <div class="min-h-[8rem] space-y-2 rounded-xl bg-gray-100/60 p-2 dark:bg-gray-800/40">
                    @forelse ($porFase[$fase->id] ?? [] as $p)
                        @include('livewire.processos.partials.kanban-card', ['p' => $p])
                    @empty
                        <p class="px-2 py-6 text-center text-xs text-gray-400">—</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
