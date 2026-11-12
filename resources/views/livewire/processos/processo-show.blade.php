<div class="space-y-6">
    @if (session('ok'))
        <div class="rounded-lg bg-green-50 px-4 py-2 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-300">{{ session('ok') }}</div>
    @endif

    <div class="flex items-start justify-between">
        <div>
            <a href="{{ route('processos.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-primary-600">← Processos</a>
            <h1 class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                {{ $processo->numero_processo ?: 'Processo #'.$processo->id }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $processo->cliente?->nome }} · {{ ucfirst($processo->area) }} · {{ $processo->tribunal }}
            </p>
        </div>
        <a href="{{ route('processos.edit', $processo) }}" wire:navigate class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
            Editar
        </a>
    </div>

    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex gap-6">
            @foreach (['dados' => 'Dados', 'partes' => 'Partes', 'andamentos' => 'Andamentos', 'prazos' => 'Prazos', 'financeiro' => 'Financeiro'] as $valor => $rotulo)
                <button wire:click="$set('aba', '{{ $valor }}')"
                        class="border-b-2 px-1 py-3 text-sm font-medium transition
                               {{ $aba === $valor ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                    {{ $rotulo }}
                    @if ($valor === 'partes') <span class="ml-1 text-xs text-gray-400">{{ $processo->partes->count() }}</span> @endif
                    @if ($valor === 'prazos') <span class="ml-1 text-xs text-gray-400">{{ $processo->prazos->where('status','pendente')->count() }}</span> @endif
                </button>
            @endforeach
        </nav>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">

        @if ($aba === 'dados')
            <dl class="grid grid-cols-1 gap-x-8 gap-y-4 md:grid-cols-2">
                @foreach ([
                    'Número (CNJ)'   => $processo->numero_processo,
                    'Pasta'          => $processo->numero_pasta,
                    'Tipo de ação'   => $processo->tipo_acao,
                    'Área'           => ucfirst($processo->area),
                    'Tribunal'       => $processo->tribunal,
                    'Comarca'        => $processo->comarca,
                    'Vara'           => $processo->vara,
                    'Instância'      => $processo->instancia,
                    'Valor da causa' => $processo->valor_causa ? 'R$ '.number_format($processo->valor_causa, 2, ',', '.') : null,
                    'Distribuição'   => $processo->data_distribuicao?->format('d/m/Y'),
                    'Responsável'    => $processo->advogado?->name,
                    'Status'         => ucfirst($processo->status),
                ] as $rotulo => $valor)
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-gray-400">{{ $rotulo }}</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $valor ?: '—' }}</dd>
                    </div>
                @endforeach
            </dl>
            @if ($processo->observacoes)
                <div class="mt-6">
                    <dt class="text-xs uppercase tracking-wide text-gray-400">Observações</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-gray-700 dark:text-gray-300">{{ $processo->observacoes }}</dd>
                </div>
            @endif
        @endif

        @if ($aba === 'partes')
            <div class="space-y-4">
                @forelse ($processo->partes as $parte)
                    <div wire:key="parte-{{ $parte->id }}" class="flex items-center justify-between rounded-lg border border-gray-100 p-3 dark:border-gray-700">
                        <div>
                            <span class="inline-flex rounded bg-primary-50 px-2 py-0.5 text-xs font-medium capitalize text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ $parte->tipo }}</span>
                            <span class="ml-2 text-sm font-medium text-gray-900 dark:text-white">{{ $parte->nome }}</span>
                            @if ($parte->cpf_cnpj) <span class="ml-2 text-xs text-gray-500">{{ $parte->cpf_cnpj }}</span> @endif
                            @if ($parte->advogado_adverso) <div class="text-xs text-gray-500">Adv. adverso: {{ $parte->advogado_adverso }}</div> @endif
                        </div>
                        <button wire:click="removerParte({{ $parte->id }})" class="text-xs text-red-500 hover:underline">Remover</button>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Nenhuma parte cadastrada.</p>
                @endforelse

                <form wire:submit="addParte" class="grid grid-cols-1 gap-3 border-t border-gray-100 pt-4 md:grid-cols-5 dark:border-gray-700">
                    <select wire:model="parte_tipo" class="rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <option value="autor">Autor</option>
                        <option value="reu">Réu</option>
                        <option value="terceiro">Terceiro</option>
                        <option value="testemunha">Testemunha</option>
                    </select>
                    <input wire:model="parte_nome" placeholder="Nome" class="rounded-lg border-gray-300 bg-gray-50 text-sm md:col-span-2 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <input wire:model="parte_cpf_cnpj" placeholder="CPF/CNPJ" class="rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <button class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-medium text-white hover:bg-primary-700">Adicionar</button>
                </form>
                @error('parte_nome') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        @endif

        @if ($aba === 'andamentos')
            <div class="space-y-4">
                <form wire:submit="addMovimentacao" class="grid grid-cols-1 gap-3 md:grid-cols-6">
                    <input type="date" wire:model="mov_data" class="rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <select wire:model="mov_tipo" class="rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <option value="andamento">Andamento</option>
                        <option value="publicacao">Publicação</option>
                        <option value="despacho">Despacho</option>
                        <option value="sentenca">Sentença</option>
                        <option value="audiencia">Audiência</option>
                    </select>
                    <input wire:model="mov_descricao" placeholder="Descrição do andamento" class="rounded-lg border-gray-300 bg-gray-50 text-sm md:col-span-3 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <button class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-medium text-white hover:bg-primary-700">Lançar</button>
                </form>
                @error('mov_descricao') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

                <ol class="relative space-y-4 border-l border-gray-200 pl-5 dark:border-gray-700">
                    @forelse ($processo->movimentacoes as $mov)
                        <li wire:key="mov-{{ $mov->id }}" class="relative">
                            <span class="absolute -left-[22px] top-1 h-2.5 w-2.5 rounded-full bg-primary-500"></span>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <span>{{ $mov->data->format('d/m/Y') }}</span>
                                <span class="rounded bg-gray-100 px-1.5 capitalize dark:bg-gray-700">{{ $mov->tipo }}</span>
                            </div>
                            <p class="text-sm text-gray-800 dark:text-gray-200">{{ $mov->descricao }}</p>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500">Nenhum andamento lançado.</li>
                    @endforelse
                </ol>
            </div>
        @endif

        @if ($aba === 'prazos')
            <div class="space-y-4">
                <form wire:submit="addPrazo" class="grid grid-cols-1 gap-3 md:grid-cols-6">
                    <input wire:model="prazo_titulo" placeholder="Título do prazo" class="rounded-lg border-gray-300 bg-gray-50 text-sm md:col-span-2 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <div>
                        <label class="block text-[10px] uppercase text-gray-400">Prazo</label>
                        <input type="date" wire:model="prazo_data" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase text-gray-400">Fatal</label>
                        <input type="date" wire:model="prazo_fatal" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    </div>
                    <select wire:model="prazo_prioridade" class="rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <option value="baixa">Baixa</option>
                        <option value="media">Média</option>
                        <option value="alta">Alta</option>
                    </select>
                    <button class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-medium text-white hover:bg-primary-700">Adicionar</button>
                </form>
                @error('prazo_titulo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                @error('prazo_data') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($processo->prazos as $prazo)
                        <div wire:key="prazo-{{ $prazo->id }}" class="flex items-center justify-between py-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    @php $pc = ['alta'=>'bg-red-500','media'=>'bg-yellow-500','baixa'=>'bg-gray-400']; @endphp
                                    <span class="h-2 w-2 rounded-full {{ $pc[$prazo->prioridade] ?? 'bg-gray-400' }}"></span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white {{ $prazo->status === 'cumprido' ? 'text-gray-400 line-through' : '' }}">{{ $prazo->titulo }}</span>
                                </div>
                                <div class="ml-4 text-xs text-gray-500">
                                    Prazo: {{ $prazo->data_prazo->format('d/m/Y') }}
                                    @if ($prazo->data_fatal) · <span class="text-red-500">Fatal: {{ $prazo->data_fatal->format('d/m/Y') }}</span> @endif
                                </div>
                            </div>
                            @if ($prazo->status !== 'cumprido')
                                <button wire:click="concluirPrazo({{ $prazo->id }})" class="rounded-lg border border-green-500 px-3 py-1 text-xs font-medium text-green-600 hover:bg-green-50 dark:hover:bg-green-900/30">Cumprir</button>
                            @else
                                <span class="text-xs text-green-600">✓ Cumprido</span>
                            @endif
                        </div>
                    @empty
                        <p class="py-3 text-sm text-gray-500">Nenhum prazo cadastrado.</p>
                    @endforelse
                </div>
            </div>
        @endif

        @if ($aba === 'financeiro')
            @php
                $receitas = $processo->lancamentos->where('tipo','receita')->sum('valor');
                $despesas = $processo->lancamentos->where('tipo','despesa')->sum('valor');
            @endphp
            <div class="space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div class="rounded-lg bg-green-50 p-4 dark:bg-green-900/20">
                        <div class="text-xs text-green-700 dark:text-green-300">Receitas</div>
                        <div class="text-lg font-semibold text-green-700 dark:text-green-300">R$ {{ number_format($receitas, 2, ',', '.') }}</div>
                    </div>
                    <div class="rounded-lg bg-red-50 p-4 dark:bg-red-900/20">
                        <div class="text-xs text-red-700 dark:text-red-300">Despesas</div>
                        <div class="text-lg font-semibold text-red-700 dark:text-red-300">R$ {{ number_format($despesas, 2, ',', '.') }}</div>
                    </div>
                    <div class="rounded-lg bg-primary-50 p-4 dark:bg-primary-900/20">
                        <div class="text-xs text-primary-700 dark:text-primary-300">Saldo</div>
                        <div class="text-lg font-semibold text-primary-700 dark:text-primary-300">R$ {{ number_format($receitas - $despesas, 2, ',', '.') }}</div>
                    </div>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($processo->lancamentos as $lanc)
                        <div wire:key="lanc-{{ $lanc->id }}" class="flex items-center justify-between py-3 text-sm">
                            <div>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $lanc->descricao }}</span>
                                <div class="text-xs text-gray-500">Venc.: {{ $lanc->vencimento?->format('d/m/Y') ?? '—' }} · {{ ucfirst($lanc->status) }}</div>
                            </div>
                            <span class="font-semibold {{ $lanc->tipo === 'receita' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $lanc->tipo === 'receita' ? '+' : '-' }} R$ {{ number_format($lanc->valor, 2, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <p class="py-3 text-sm text-gray-500">Nenhum lançamento vinculado. (Módulo Financeiro vem a seguir.)</p>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</div>
