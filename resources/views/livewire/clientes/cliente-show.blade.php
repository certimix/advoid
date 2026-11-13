<div class="mx-auto max-w-4xl space-y-6">
    @if (session('ok'))
        <div class="rounded-lg bg-green-50 px-4 py-2 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-300">{{ session('ok') }}</div>
    @endif

    <div class="flex items-start justify-between">
        <div>
            <a href="{{ route('clientes.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-primary-600">← Clientes</a>
            <h1 class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $cliente->nome }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $cliente->tipo === 'juridica' ? 'Pessoa jurídica' : 'Pessoa física' }}
                @if ($cliente->cpf_cnpj) · {{ $cliente->cpf_cnpj }} @endif
                @unless ($cliente->ativo) · <span class="text-red-500">inativo</span> @endunless
            </p>
        </div>
        <a href="{{ route('clientes.edit', $cliente) }}" wire:navigate class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Editar</a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <dl class="grid grid-cols-1 gap-x-8 gap-y-4 md:grid-cols-2">
            @foreach ([
                'E-mail'   => $cliente->email,
                'Telefone' => $cliente->telefone,
                'Celular'  => $cliente->celular,
                'Endereço' => trim(($cliente->logradouro ? $cliente->logradouro.', '.$cliente->numero : '').' '.($cliente->bairro ?? '')),
                'Cidade'   => $cliente->cidade ? $cliente->cidade.'/'.$cliente->uf : null,
                'CEP'      => $cliente->cep,
            ] as $rotulo => $valor)
                <div>
                    <dt class="text-xs uppercase tracking-wide text-gray-400">{{ $rotulo }}</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ $valor ?: '—' }}</dd>
                </div>
            @endforeach
        </dl>
        @if ($cliente->observacoes)
            <div class="mt-6">
                <dt class="text-xs uppercase tracking-wide text-gray-400">Observações</dt>
                <dd class="mt-1 whitespace-pre-line text-sm text-gray-700 dark:text-gray-300">{{ $cliente->observacoes }}</dd>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Processos</h2>
                <a href="{{ route('processos.create') }}" wire:navigate class="text-xs text-primary-600 hover:underline">+ Novo</a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($cliente->processos as $p)
                    <a href="{{ route('processos.show', $p) }}" wire:navigate wire:key="cp-{{ $p->id }}" class="block py-2 text-sm hover:text-primary-600">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $p->numero_processo ?: 'Proc. #'.$p->id }}</div>
                        <div class="text-xs text-gray-400">{{ ucfirst($p->area) }} · {{ ucfirst($p->status) }}</div>
                    </a>
                @empty
                    <p class="py-2 text-sm text-gray-500">Nenhum processo.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Atendimentos</h2>
                <a href="{{ route('atendimentos.create') }}" wire:navigate class="text-xs text-primary-600 hover:underline">+ Novo</a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($cliente->atendimentos as $a)
                    <a href="{{ route('atendimentos.show', $a) }}" wire:navigate wire:key="ca-{{ $a->id }}" class="block py-2 text-sm hover:text-primary-600">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $a->assunto }}</div>
                        <div class="text-xs text-gray-400">{{ str_replace('_', ' ', ucfirst($a->status)) }}</div>
                    </a>
                @empty
                    <p class="py-2 text-sm text-gray-500">Nenhum atendimento.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
