<?php

namespace App\Livewire\Financeiro;

use App\Models\LancamentoFinanceiro;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class FinanceiroIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $busca = '';

    #[Url]
    public string $tipo = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $periodo = 'este_mes';

    public function updating($name): void
    {
        if ($name !== 'page') {
            $this->resetPage();
        }
    }

    public function marcarPago(int $id): void
    {
        $lanc = LancamentoFinanceiro::where('escritorio_id', Auth::user()->escritorio_id)->findOrFail($id);
        $lanc->update([
            'status'     => 'pago',
            'pago_em'    => now(),
            'valor_pago' => $lanc->valor,
        ]);
    }

    public function limparFiltros(): void
    {
        $this->reset(['busca', 'tipo', 'status']);
        $this->periodo = 'todos';
        $this->resetPage();
    }

    protected function periodoBounds(): array
    {
        return match ($this->periodo) {
            'hoje'         => [now()->startOfDay(), now()->endOfDay()],
            'esta_semana'  => [now()->startOfWeek(), now()->endOfWeek()],
            'este_mes'     => [now()->startOfMonth(), now()->endOfMonth()],
            'mes_passado'  => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'proximos_30'  => [now()->startOfDay(), now()->addDays(30)->endOfDay()],
            default        => [null, null],
        };
    }

    protected function baseQuery()
    {
        [$inicio, $fim] = $this->periodoBounds();

        return LancamentoFinanceiro::query()
            ->where('escritorio_id', Auth::user()->escritorio_id)
            ->when($this->periodo === 'vencidos', fn ($q) => $q->whereDate('vencimento', '<', now())->whereIn('status', ['pendente', 'pago_parcial']))
            ->when($inicio, fn ($q) => $q->whereBetween('vencimento', [$inicio, $fim]))
            ->when($this->tipo, fn ($q) => $q->where('tipo', $this->tipo))
            ->when($this->status === 'em_atraso', fn ($q) => $q->whereDate('vencimento', '<', now())->where('status', 'pendente'))
            ->when($this->status && $this->status !== 'em_atraso', fn ($q) => $q->where('status', $this->status))
            ->when($this->busca, fn ($q) => $q->where('descricao', 'like', "%{$this->busca}%"));
    }

    public function render()
    {
        $lancamentos = $this->baseQuery()
            ->with(['cliente', 'processo', 'categoria', 'responsavel'])
            ->orderBy('vencimento')
            ->paginate(15);

        $resumo = (clone $this->baseQuery());
        $receitas = (clone $resumo)->where('tipo', 'receita')->sum('valor');
        $despesas = (clone $resumo)->where('tipo', 'despesa')->sum('valor');
        $pago     = (clone $resumo)->where('status', 'pago')->sum('valor_pago');
        $atraso   = LancamentoFinanceiro::where('escritorio_id', Auth::user()->escritorio_id)
                        ->whereDate('vencimento', '<', now())->where('status', 'pendente')->count();

        return view('livewire.financeiro.financeiro-index', [
            'lancamentos' => $lancamentos,
            'receitas'    => $receitas,
            'despesas'    => $despesas,
            'saldo'       => $receitas - $despesas,
            'pago'        => $pago,
            'atraso'      => $atraso,
        ]);
    }
}
