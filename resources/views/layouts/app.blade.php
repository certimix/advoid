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
<body class="h-full bg-gray-50 text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
@php
    $nav = [
        ['dashboard', 'Painel', 'M2.25 12 12 3l9.75 9M4.5 10.5V20a1 1 0 0 0 1 1h4v-6h5v6h4a1 1 0 0 0 1-1v-9.5'],
        ['processos.index', 'Processos', 'M3.75 6.75A2.25 2.25 0 0 1 6 4.5h3.3a1.5 1.5 0 0 1 1.06.44l1.2 1.2a1.5 1.5 0 0 0 1.06.44H18a2.25 2.25 0 0 1 2.25 2.25v8.25A2.25 2.25 0 0 1 18 19.5H6a2.25 2.25 0 0 1-2.25-2.25Z'],
        ['processos.kanban', 'Kanban', 'M4 5h4v14H4zM10 5h4v9h-4zM16 5h4v6h-4z'],
        ['clientes.index', 'Clientes', 'M15 19.5a6 6 0 0 0-12 0M9 11.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM17.25 19.5a5 5 0 0 0-3-4.58M15.75 11a2.5 2.5 0 1 0 0-5'],
        ['atendimentos.index', 'Atendimentos', 'M4.5 5.25h15a1.5 1.5 0 0 1 1.5 1.5v8.25a1.5 1.5 0 0 1-1.5 1.5H9l-4.5 3.75V6.75a1.5 1.5 0 0 1 1.5-1.5Z'],
        ['agenda.index', 'Agenda', 'M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6h15a1.5 1.5 0 0 1 1.5 1.5V19.5A1.5 1.5 0 0 1 19.5 21h-15A1.5 1.5 0 0 1 3 19.5V7.5A1.5 1.5 0 0 1 4.5 6Z'],
        ['financeiro.index', 'Financeiro', 'M3 6.75h18v10.5H3zM12 14.25a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z'],
        ['dna.index', 'DNA', 'M6 3.75h8.25L18 7.5v12.75a.75.75 0 0 1-.75.75H6a.75.75 0 0 1-.75-.75V4.5A.75.75 0 0 1 6 3.75ZM13.5 3.75V7.5H18M8.25 12h7.5M8.25 15.75h7.5'],
        ['publicacoes.index', 'Publicações', 'M15 17.25a3 3 0 0 1-6 0M18 9A6 6 0 0 0 6 9c0 4.5-2.25 5.25-2.25 5.25h16.5S18 13.5 18 9Z'],
        ['equipes.index', 'Equipes', 'M9 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3 19.5a6 6 0 0 1 12 0M16.5 11.25a2.5 2.5 0 1 0 0-5M16.5 13.5a5 5 0 0 1 4.5 2.78'],
    ];
    $prefixAtivo = fn ($rota) => request()->routeIs(explode('.', $rota)[0] . '*');
@endphp

    <div class="min-h-full">
        {{-- Top bar escura --}}
        <header class="sticky top-0 z-30 bg-slate-900 text-white shadow">
            <div class="mx-auto flex h-14 max-w-screen-2xl items-center gap-4 px-4">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 pr-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-600 text-sm font-bold">A</span>
                    <span class="text-lg font-semibold tracking-tight">Advoid</span>
                </a>

                <nav class="flex flex-1 items-center gap-0.5 overflow-x-auto">
                    @foreach ($nav as [$rota, $rotulo, $icone])
                        <a href="{{ route($rota) }}" wire:navigate
                           class="flex shrink-0 items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition
                                  {{ $prefixAtivo($rota) ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icone }}" />
                            </svg>
                            <span class="hidden lg:inline">{{ $rotulo }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="flex shrink-0 items-center gap-3">
                    <span class="hidden text-sm text-slate-300 sm:inline">{{ auth()->user()?->name }}</span>
                    <a href="{{ route('escritorio.settings') }}" wire:navigate
                       class="rounded-lg px-2 py-1.5 text-sm text-slate-300 hover:bg-white/5 hover:text-white" title="Meu escritório">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.28c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.03 7.03 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.998 6.998 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded-lg border border-white/20 px-3 py-1.5 text-sm text-slate-200 hover:bg-white/10">Sair</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-screen-2xl px-4 py-8">
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
</body>
</html>
