<?php

namespace App\Livewire\Financeiro;

use App\Models\CategoriaFinanceira;
use App\Models\Cliente;
use App\Models\LancamentoFinanceiro;
use App\Models\Processo;
use App\Services\Ai\AiService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class LancamentoForm extends Component
{
    use WithFileUploads;

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

    public $comprovante = null;
    public bool $iaDisponivel = false;
    public ?string $iaMsg = null;
    public ?string $iaErro = null;

    public function mount(?LancamentoFinanceiro $lancamento = null): void
    {
        $this->iaDisponivel = app(AiService::class)->disponivel();

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

    public function updatedComprovante(): void
    {
        $this->iaMsg = $this->iaErro = null;
        $this->validateOnly('comprovante');

        if (! $this->iaDisponivel) {
            return;
        }

        $mime = $this->comprovante->getMimeType();
        if (! str_starts_with((string) $mime, 'image/')) {
            $this->iaMsg = 'Comprovante anexado. Leitura automática só funciona com imagem (JPG/PNG).';
            return;
        }

        try {
            $dados = app(AiService::class)->extrairComprovante($this->comprovante->getRealPath(), $mime);

            if (! empty($dados['tipo']) && in_array($dados['tipo'], ['receita', 'despesa'], true)) {
                $this->tipo = $dados['tipo'];
            }
            if (! empty($dados['descricao'])) {
                $this->descricao = $dados['descricao'];
            }
            if (isset($dados['valor']) && is_numeric($dados['valor'])) {
                $this->valor = (string) $dados['valor'];
            }
            if (! empty($dados['vencimento'])) {
                $this->vencimento = $dados['vencimento'];
            }
            if (! empty($dados['forma_pagamento'])) {
                $this->forma_pagamento = $dados['forma_pagamento'];
            }

            $this->iaMsg = 'Campos preenchidos a partir do comprovante — confira antes de salvar.';
        } catch (\Throwable $e) {
            $this->iaErro = 'Não consegui ler o comprovante automaticamente. Preencha manualmente.';
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
            'comprovante'     => ['nullable', 'file', 'max:15360'],
        ];
    }

    public function save()
    {
        $dados = $this->validate();
        unset($dados['comprovante']);
        $dados['escritorio_id'] = Auth::user()->escritorio_id;
        $dados['responsavel_id'] = Auth::id();

        if ($this->comprovante) {
            $dados['comprovante_path'] = $this->comprovante->store('comprovantes', 'public');
        }

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
