<div class="mx-auto max-w-3xl space-y-6">
    @if (session('ok'))
        <div class="rounded-lg bg-green-50 px-4 py-2 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-300">{{ session('ok') }}</div>
    @endif

    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Meu escritório</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Dados do escritório usados nos documentos e no cabeçalho.</p>
    </div>

    <form wire:submit="save" class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        {{-- Logo --}}
        <div class="flex items-center gap-4">
            <div class="flex h-20 w-20 items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
                @if ($logo)
                    <img src="{{ $logo->temporaryUrl() }}" class="h-full w-full object-contain" alt="prévia">
                @elseif ($escritorio->logo_path)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($escritorio->logo_path) }}" class="h-full w-full object-contain" alt="logo">
                @else
                    <span class="text-xs text-gray-400">sem logo</span>
                @endif
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Logo do escritório</label>
                <input type="file" wire:model="logo" accept="image/*" class="text-sm text-gray-600 dark:text-gray-300">
                <div wire:loading wire:target="logo" class="text-xs text-gray-400">Enviando...</div>
                @error('logo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                <p class="mt-1 text-xs text-gray-400">PNG/JPG até 2MB. (Requer <code>php artisan storage:link</code>.)</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Nome *</label>
                <input type="text" wire:model="nome" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                @error('nome') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">CNPJ</label>
                <input type="text" wire:model="cnpj" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">OAB responsável</label>
                <input type="text" wire:model="oab_responsavel" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">E-mail</label>
                <input type="email" wire:model="email" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone</label>
                <input type="text" wire:model="telefone" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4 dark:border-gray-700">
            <h3 class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Endereço</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-6">
                <div class="md:col-span-2"><label class="mb-1 block text-xs text-gray-500">CEP</label><input type="text" wire:model="cep" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
                <div class="md:col-span-3"><label class="mb-1 block text-xs text-gray-500">Logradouro</label><input type="text" wire:model="logradouro" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
                <div><label class="mb-1 block text-xs text-gray-500">Número</label><input type="text" wire:model="numero" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
                <div class="md:col-span-2"><label class="mb-1 block text-xs text-gray-500">Complemento</label><input type="text" wire:model="complemento" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
                <div class="md:col-span-2"><label class="mb-1 block text-xs text-gray-500">Bairro</label><input type="text" wire:model="bairro" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
                <div><label class="mb-1 block text-xs text-gray-500">Cidade</label><input type="text" wire:model="cidade" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
                <div><label class="mb-1 block text-xs text-gray-500">UF</label><input type="text" maxlength="2" wire:model="uf" class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm uppercase dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-medium text-white hover:bg-primary-700">
                <span wire:loading.remove wire:target="save">Salvar</span>
                <span wire:loading wire:target="save">Salvando...</span>
            </button>
        </div>
    </form>
</div>
