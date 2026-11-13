<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Gerar peça com IA</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Usa os blocos do DNA e os dados do processo para redigir no estilo do escritório.</p>
        </div>
        <a href="{{ route('dna.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-primary-600">← DNA</a>
    </div>

    @unless ($iaDisponivel)
        <div class="rounded-lg bg-yellow-50 px-4 py-3 text-sm text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
            IA desligada. Defina <code>AI_API_KEY</code> no <code>.env</code> para gerar peças.
        </div>
    @endunless

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Entrada --}}
        <div class="space-y-4 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Processo (contexto)</label>
                    <select wire:model="processo_id" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <option value="">Nenhum</option>
                        @foreach ($processos as $p)
                            <option value="{{ $p->id }}">{{ $p->numero_processo ?: 'Proc. #'.$p->id }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de peça</label>
                    <input type="text" wire:model="tipo_peca" placeholder="Contestação, recurso..." class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Blocos do DNA a usar</label>
                <div class="max-h-40 space-y-1 overflow-y-auto rounded-lg border border-gray-200 p-2 dark:border-gray-700">
                    @forelse ($modelos as $m)
                        <label class="flex items-center gap-2 rounded px-2 py-1 text-sm hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <input type="checkbox" value="{{ $m->id }}" wire:model="blocos" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-gray-800 dark:text-gray-200">{{ $m->titulo }}</span>
                            <span class="ml-auto text-xs text-gray-400">{{ $m->categoria }}</span>
                        </label>
                    @empty
                        <p class="px-2 py-1 text-xs text-gray-400">Nenhum bloco no DNA ainda.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Instruções *</label>
                <textarea wire:model="instrucoes" rows="5" placeholder="Ex.: contestar alegando prescrição e ausência de provas; pedir improcedência." class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea>
                @error('instrucoes') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            @if ($erro) <div class="rounded bg-red-50 px-3 py-2 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-300">{{ $erro }}</div> @endif

            <button wire:click="gerar" @disabled(! $iaDisponivel)
                    class="w-full rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 disabled:opacity-50">
                <span wire:loading.remove wire:target="gerar">Gerar peça</span>
                <span wire:loading wire:target="gerar">Redigindo...</span>
            </button>
        </div>

        {{-- Saída --}}
        <div class="space-y-3 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Resultado</h2>
                @if ($resultado)
                    <button wire:click="salvarRascunho" class="rounded-lg border border-primary-500 px-3 py-1 text-xs font-medium text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30">Salvar rascunho no DNA</button>
                @endif
            </div>
            <textarea wire:model="resultado" rows="22" placeholder="A peça gerada aparece aqui — você pode editar antes de salvar." class="w-full rounded-lg border-gray-300 bg-gray-50 font-mono text-sm leading-relaxed dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea>
        </div>
    </div>
</div>
