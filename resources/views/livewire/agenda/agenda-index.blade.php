<div class="space-y-6">
    @if (session('ok'))
        <div class="rounded-lg bg-green-50 px-4 py-2 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-300">{{ session('ok') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Agenda</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Audiências, julgamentos, perícias e compromissos do escritório.</p>
        </div>
        <a href="{{ route('agenda.create') }}" wire:navigate
           class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-700">
            + Novo compromisso
        </a>
    </div>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
        @foreach ([
            'audiencia' => 'Audiências', 'julgamento' => 'Julgamentos', 'pericia' => 'Perícias',
            'reuniao' => 'Reuniões', 'diligencia' => 'Diligências', 'tarefa' => 'Tarefas',
        ] as $t => $rotulo)
            <button wire:click="$set('tipo', '{{ $tipo === $t ? '' : $t }}')"
                    class="rounded-xl border p-3 text-left transition
                           {{ $tipo === $t ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-200 bg-white hover:border-primary-300 dark:border-gray-700 dark:bg-gray-800' }}">
                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $contagem[$t] ?? 0 }}</div>
                <div class="text-xs text-gray-500">{{ $rotulo }}</div>
            </button>
        @endforeach
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <input type="text" wire:model.live.debounce.400ms="busca" placeholder="Buscar..."
               class="w-56 rounded-lg border-gray-300 bg-gray-50 text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
        @foreach (['hoje'=>'Hoje','semana'=>'Semana','mes'=>'Mês','todos'=>'Todos'] as $p => $r)
            <button wire:click="$set('periodo', '{{ $p }}')"
                    class="rounded-lg px-3 py-1.5 text-sm font-medium transition {{ $periodo === $p ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">
                {{ $r }}
            </button>
        @endforeach
        <select wire:model.live="status" class="rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            <option value="agendado">Agendados</option>
            <option value="realizado">Realizados</option>
            <option value="cancelado">Cancelados</option>
            <option value="todos">Todos</option>
        </select>
        <button wire:click="limparFiltros" class="text-sm text-gray-500 hover:text-primary-600">✕ Limpar</button>
    </div>

    <div class="space-y-6">
        @forelse ($compromissos as $dia => $itens)
            <div>
                <h2 class="mb-2 text-sm font-semibold text-gray-500 dark:text-gray-400">
                    {{ \Illuminate\Support\Carbon::parse($dia)->translatedFormat('l, d \d\e F') }}
                </h2>
                <div class="space-y-2">
                    @foreach ($itens as $c)
                        @php
                            $tcores = [
                                'audiencia' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
                                'julgamento' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                                'pericia' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                                'reuniao' => 'bg-teal-100 text-teal-700 dark:bg-teal-900/40 dark:text-teal-300',
                                'diligencia' => 'bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-300',
                                'tarefa' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                            ];
                        @endphp
                        <div wire:key="comp-{{ $c->id }}" class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                            <div class="w-14 shrink-0 text-center">
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $c->inicio->format('H:i') }}</div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex rounded px-2 py-0.5 text-xs font-medium capitalize {{ $tcores[$c->tipo] ?? 'bg-gray-100 text-gray-600' }}">{{ $c->tipo }}</span>
                                    <span class="font-medium text-gray-900 dark:text-white {{ $c->status === 'cancelado' ? 'text-gray-400 line-through' : '' }}">{{ $c->titulo }}</span>
                                </div>
                                <div class="mt-0.5 text-xs text-gray-500">
                                    @if ($c->local) {{ $c->local }} · @endif
                                    {{ $c->responsavel?->name }}
                                    @if ($c->processo) · Proc. {{ $c->processo->numero_processo ?: '#'.$c->processo->id }} @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if ($c->status === 'agendado')
                                    <button wire:click="marcarRealizado({{ $c->id }})" class="rounded-lg border border-green-500 px-2.5 py-1 text-xs font-medium text-green-600 hover:bg-green-50 dark:hover:bg-green-900/30">✓ Realizado</button>
                                    <a href="{{ route('agenda.edit', $c) }}" wire:navigate class="rounded-lg border border-gray-300 px-2.5 py-1 text-xs text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300">Editar</a>
                                    <button wire:click="cancelar({{ $c->id }})" class="text-xs text-red-500 hover:underline">Cancelar</button>
                                @elseif ($c->status === 'realizado')
                                    <span class="text-xs font-medium text-green-600">✓ Realizado</span>
                                @else
                                    <span class="text-xs text-gray-400">Cancelado</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-gray-300 p-12 text-center text-sm text-gray-500 dark:border-gray-600">
                Nenhum compromisso no período.
            </div>
        @endforelse
    </div>
</div>
