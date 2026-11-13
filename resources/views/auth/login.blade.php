<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar — Advoid</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-full items-center justify-center bg-[#f6f6f4] px-4">
    <div class="w-full max-w-sm">
        <div class="mb-6 flex flex-col items-center text-center">
            <span class="mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-700 text-white shadow-sm">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75v15.5M8.5 19.25h7M5 7h14M12 7 5 7m0 0-2.4 5.2a2.4 2.4 0 0 0 4.8 0L5 7Zm14 0-2.4 5.2a2.4 2.4 0 0 0 4.8 0L19 7Z" />
                </svg>
            </span>
            <h1 class="font-serif text-3xl font-semibold tracking-tight text-gray-900">Advoid</h1>
            <p class="mt-1 text-sm text-gray-500">Gestão de escritório de advocacia</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-7 shadow-sm">
            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Senha</label>
                    <input type="password" name="password" required
                           class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    Lembrar de mim
                </label>
                <button type="submit" class="w-full rounded-lg bg-primary-700 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-primary-800">Entrar</button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-gray-400">Advoid · {{ date('Y') }}</p>
    </div>
</body>
</html>
