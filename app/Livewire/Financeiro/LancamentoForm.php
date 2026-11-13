<?php

namespace App\Livewire\Financeiro;

use App\Models\CategoriaFinanceira;
use App\Models\Cliente;
use App\Models\LancamentoFinanceiro;
use App\Models\Processo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class LancamentoForm extends Component
{
    public ?LancamentoFinanceiro $lancamento = null;

    public string $tipo = 'receita';
    public string $descricao = '';
    public ?string $valor = null;
    public ?string $vencimento = null;
    public ?int $categoria_id = null;
    public ?int $cliente_id = null;
    public ?int $processo_id = null;
    public string $status = 'pendente';
    public ?string $forma_pagamento = null;
    public ?string $observacoes = null;

    public function mount(?LancamentoFinanceiro $lancamento = null): void
    {
        if ($lancamento && $lancamento->exists) {
            abort_unless($lancamento->escritorio_id === Auth::user()->escritorio_id, 403);
            $this->lancamento = $lancamento;
            $this->fill($lancamento->only([
                'tipo', 'descricao', 'valor', 'categoria_id', 'cliente_id', 'processo_id',
                'status', 'forma_pagamento', 'observacoes',
            ]));
            $this->vencimento = optional($lancamento->vencimento)->format('Y-m-d');
        } else {
            $this->vencimento = now()->format('Y-m-d');
        }
    }

    protected function rules(): array
    {
        return [
            'tipo'            => ['required', 'in:receita,despesa,transferencia'],
            'descricao'       => ['required', 'string', 'max:255'],
            'valor'           => ['required', 'numeric', 'min:0'],
            'vencimento'      => ['nullable', 'date'],
            'categoria_id'    => ['nullable', 'exists:categorias_financeiras,id'],
            'cliente_id'      => ['nullable', 'exists:clientes,id'],
            'processo_id'     => ['nullable', 'exists:processos,id'],
            'status'          => ['required', 'string'],
            'forma_pagamento' => ['nullable', 'string', 'max:255'],
            'observacoes'     => ['nullable', 'string'],
        ];
    }

    public function save()
    {
        $dados = $this->validate();
        $dados['escritorio_id'] = Auth::user()->escritorio_id;
        $dados['responsavel_id'] = Auth::id();

        if ($this->lancamento) {
            $this->lancamento->update($dados);
        } else {
            LancamentoFinanceiro::create($dados);
        }

        session()->flash('ok', 'Lançamento salvo.');

        return $this->redirectRoute('financeiro.index', navigate: true);
    }

    public function render()
    {
        $eid = Auth::user()->escritorio_id;

        return view('livewire.financeiro.lancamento-form', [
            'categorias' => CategoriaFinanceira::where('escritorio_id', $eid)->orderBy('nome')->get(),
            'clientes'   => Cliente::where('escritorio_id', $eid)->orderBy('nome')->get(),
            'processos'  => Processo::where('escritorio_id', $eid)->orderByDesc('id')->get(),
        ]);
    }
}
