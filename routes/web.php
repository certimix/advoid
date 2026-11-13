<?php

use App\Livewire\Agenda\AgendaIndex;
use App\Livewire\Agenda\CompromissoForm;
use App\Livewire\Atendimentos\AtendimentoForm;
use App\Livewire\Atendimentos\AtendimentoIndex;
use App\Livewire\Atendimentos\AtendimentoShow;
use App\Livewire\Dashboard;
use App\Livewire\Processos\ProcessoForm;
use App\Livewire\Processos\ProcessoIndex;
use App\Livewire\Processos\ProcessoShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route(Auth::check() ? 'dashboard' : 'login'));

/* Autenticação simples (sem Breeze) */
Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');

    Route::post('/login', function (Request $request) {
        $cred = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($cred, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email' => 'Credenciais inválidas.'])->onlyInput('email');
    });
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

/* Aplicação */
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::get('/processos', ProcessoIndex::class)->name('processos.index');
    Route::get('/processos/novo', ProcessoForm::class)->name('processos.create');
    Route::get('/processos/{processo}/editar', ProcessoForm::class)->name('processos.edit');
    Route::get('/processos/{processo}', ProcessoShow::class)->name('processos.show');

    Route::get('/atendimentos', AtendimentoIndex::class)->name('atendimentos.index');
    Route::get('/atendimentos/novo', AtendimentoForm::class)->name('atendimentos.create');
    Route::get('/atendimentos/{atendimento}/editar', AtendimentoForm::class)->name('atendimentos.edit');
    Route::get('/atendimentos/{atendimento}', AtendimentoShow::class)->name('atendimentos.show');

    Route::get('/agenda', AgendaIndex::class)->name('agenda.index');
    Route::get('/agenda/novo', CompromissoForm::class)->name('agenda.create');
    Route::get('/agenda/{compromisso}/editar', CompromissoForm::class)->name('agenda.edit');
});

Route::middleware('auth')->group(function () {
    Route::get('/financeiro', \App\Livewire\Financeiro\FinanceiroIndex::class)->name('financeiro.index');
    Route::get('/financeiro/novo', \App\Livewire\Financeiro\LancamentoForm::class)->name('financeiro.create');
    Route::get('/financeiro/{lancamento}/editar', \App\Livewire\Financeiro\LancamentoForm::class)->name('financeiro.edit');
});
