<div wire:key="kb-{{ $p->id }}"
     draggable="true"
     @dragstart="dragId = {{ $p->id }}"
     class="cursor-grab rounded-lg border border-gray-200 bg-white p-3 shadow-sm active:cursor-grabbing dark:border-gray-700 dark:bg-gray-800">
    <a href="{{ route('processos.show', $p) }}" wire:navigate class="block">
        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $p->numero_processo ?: 'Proc. #'.$p->id }}</div>
        <div class="mt-0.5 text-xs text-gray-500">{{ $p->cliente?->nome }}</div>
        @if ($p->area)
            <span class="mt-2 inline-flex rounded bg-primary-50 px-1.5 py-0.5 text-[10px] font-medium capitalize text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ $p->area }}</span>
        @endif
    </a>
</div>
