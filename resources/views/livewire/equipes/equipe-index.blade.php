<div class="space-y-6">
    @if (session('ok'))
        <div class="rounded-lg bg-green-50 px-4 py-2 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-300">{{ session('ok') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Equipes</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Gerencie as equipes e seus membros.</p>
        </div>
        <a href="{{ route('equipes.create') }}" wire:navigate class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">+ Nova equipe</a>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($equipes as $equipe)
            <div wire:key="eq-{{ $equipe->id }}" class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-start justify-between">
                    <h2 class="font-medium text-gray-900 dark:text-white">{{ $equipe->nome }}</h2>
                    <span class="rounded-full bg-primary-50 px-2 py-0.5 text-xs font-medium text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ $equipe->membros_count }} membros</span>
                </div>
                @if ($equipe->descricao)
                    <p class="mt-1 text-xs text-gray-500">{{ $equipe->descricao }}</p>
                @endif
                <div class="mt-3 flex flex-wrap gap-1">
                    @foreach ($equipe->membros as $m)
                        <span class="rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ $m->name }}</span>
                    @endforeach
                </div>
                <div class="mt-3 flex items-center justify-end gap-3 border-t border-gray-100 pt-2 dark:border-gray-700">
                    <a href="{{ route('equipes.edit', $equipe) }}" wire:navigate class="text-xs text-primary-600 hover:underline">Editar</a>
                    <button wire:click="excluir({{ $equipe->id }})" wire:confirm="Excluir esta equipe?" class="text-xs text-red-500 hover:underline">Excluir</button>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-xl border border-dashed border-gray-300 p-12 text-center text-sm text-gray-500 dark:border-gray-600">
                Nenhuma equipe cadastrada.
            </div>
        @endforelse
    </div>
</div>
