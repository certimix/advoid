<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Advoid' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50 text-gray-900 antialiased dark:bg-gray-900 dark:text-gray-100">
    <div class="min-h-full">
        <header class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-800">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-xl font-bold text-primary-600">Advoid</a>
                    <nav class="hidden gap-1 md:flex">
                        @php
                            $nav = [
                                'dashboard' => 'Painel',
                                'processos.index' => 'Processos',
                                'atendimentos.index' => 'Atendimentos',
                                'agenda.index' => 'Agenda',
                            ];
                        @endphp
                        @foreach ($nav as $rota => $rotulo)
                            <a href="{{ route($rota) }}" wire:navigate
                               class="rounded-lg px-3 py-2 text-sm font-medium transition
                                      {{ request()->routeIs(explode('.', $rota)[0].'*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                {{ $rotulo }}
                            </a>
                        @endforeach
                    </nav>
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden text-sm text-gray-500 sm:inline dark:text-gray-400">{{ auth()->user()?->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Sair</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8">
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
</body>
</html>
