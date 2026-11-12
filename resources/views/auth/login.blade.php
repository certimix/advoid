<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar — Advoid</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-full items-center justify-center bg-gray-50 dark:bg-gray-900">
    <div class="w-full max-w-sm rounded-2xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h1 class="mb-1 text-center text-2xl font-bold text-primary-600">Advoid</h1>
        <p class="mb-6 text-center text-sm text-gray-500">Gestão de escritório de advocacia</p>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-300">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Senha</label>
                <input type="password" name="password" required
                       class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                Lembrar de mim
            </label>
            <button type="submit" class="w-full rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">Entrar</button>
        </form>
    </div>
</body>
</html>
