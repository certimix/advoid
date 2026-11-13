<div class="mx-auto max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ $lancamento ? 'Editar lançamento' : 'Novo lançamento' }}
        </h1>
        <a href="{{ route('financeiro.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-primary-600">← Voltar</a>
    </div>

    {{-- Comprovante + IA --}}
    <div class="rounded-xl border border-dashed border-primary-300 bg-primary-50/40 p-4 dark:border-primary-700 dark:bg-primary-900/10">
        <label class="block cursor-pointer text-center">
            <input type="file" wire:model="comprovante" accept="image/*,application/pdf" class="hidden">
            <div class="py-2">
                <div class="text-sm font-medium text-primary-700 dark:text-primary-300">
                    @if ($iaDisponivel) Arraste o comprovante ou clique — a IA preenche @else Anexar comprovante @endif
                </div>
                <div class="text-xs text-gray-500">PDF, JPG ou PNG até 15MB @unless($iaDisponivel) · (leitura por IA desligada — configure AI_API_KEY) @endunless</div>
            </div>
        </label>
        <div wire:loading wire:target="comprovante" class="text-center text-xs text-primary-600">Lendo comprovante...</div>
        @if ($comprovante && ! $errors->has('comprovante'))
            <div class="mt-1 text-center text-xs text-gray-500">✓ {{ $comprovante->getClientOriginalName() }}</div>
        @endif
        @error('comprovante') <div class="mt-1 text-center text-xs text-red-500">{{ $message }}</div> @enderror
        @if ($iaMsg) <div class="mt-2 rounded bg-green-50 px-3 py-1.5 text-center text-xs text-green-700 dark:bg-green-900/30 dark:text-green-300">{{ $iaMsg }}</div> @endif
        @if ($iaErro) <div class="mt-2 rounded bg-red-50 px-3 py-1.5 text-center text-xs text-red-700 dark:bg-red-900/30 dark:text-red-300">{{ $iaErro }}</div> @endif
    </div>

    <form wire:submit="save" class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo</label>
            <div class="grid grid-cols-3 gap-3">
                @foreach (['receita'=>'Receita','despesa'=>'Despesa','transferencia'=>'Transferência'] as $v => $r)
                    <button type="button" wire:click="$set('tipo', '{{ $v }}')"
                            class="rounded-lg border px-3 py-2 text-sm font-medium transition
                                   {{ $tipo === $v ? 'border-primary-500 bg-primary-50 text-primary-700 dark:bg-primary-900/20 dark:text-primary-300' : 'border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        {{ $r }}
                    </button>
                @endforeach
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição *</label>
            <input type="text" wire:model="descricao" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            @error('descricao') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Valor (R$) *</label>
                <input type="number" step="0.01" wire:model="valor" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                @error('valor') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Vencimento</label>
                <input type="date" wire:model="vencimento" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                <select wire:model="categoria_id" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Sem categoria</option>
                    @foreach ($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select wire:model="status" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="pendente">Pendente</option>
                    <option value="pago_parcial">Pago parcial</option>
                    <option value="pago">Pago / Recebido</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                <select wire:model="cliente_id" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Nenhum</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Processo</label>
                <select wire:model="processo_id" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Nenhum</option>
                    @foreach ($processos as $p)
                        <option value="{{ $p->id }}">{{ $p->numero_processo ?: 'Proc. #'.$p->id }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Forma de pagamento</label>
                <input type="text" wire:model="forma_pagamento" placeholder="PIX, boleto, cartão..." class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
            <textarea wire:model="observacoes" rows="3" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('financeiro.index') }}" wire:navigate class="rounded-lg px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Cancelar</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-medium text-white hover:bg-primary-700">
                <span wire:loading.remove wire:target="save">Salvar</span>
                <span wire:loading wire:target="save">Salvando...</span>
            </button>
        </div>
    </form>
</div>
