<?php

namespace App\Livewire\Atendimentos;

use App\Models\Atendimento;
use App\Models\Processo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AtendimentoShow extends Component
{
    public Atendimento $atendimento;

    public function mount(Atendimento $atendimento): void
    {
        abort_unless($atendimento->escritorio_id === Auth::user()->escritorio_id, 403);
        $this->atendimento = $atendimento;
    }

    public function converterEmProcesso()
    {
        if ($this->atendimento->processo_id) {
            return $this->redirectRoute('processos.show', $this->atendimento->processo_id, navigate: true);
        }

        if (! $this->atendimento->cliente_id) {
            session()->flash('erro', 'Defina um cliente no atendimento antes de converter em processo.');
            return;
        }

        $processo = Processo::create([
            'escritorio_id' => $this->atendimento->escritorio_id,
            'cliente_id'    => $this->atendimento->cliente_id,
            'advogado_id'   => $this->atendimento->responsavel_id,
            'area'          => $this->atendimento->area,
            'tipo_acao'     => $this->atendimento->assunto,
            'fase'          => 'inicial',
            'status'        => 'ativo',
            'observacoes'   => $this->atendimento->descricao,
        ]);

        $this->atendimento->update([
            'processo_id' => $processo->id,
            'status'      => 'virou_processo',
        ]);

        session()->flash('ok', 'Atendimento convertido em processo.');

        return $this->redirectRoute('processos.show', $processo, navigate: true);
    }

    public function encerrar(): void
    {
        $this->atendimento->update(['status' => 'encerrado']);
        $this->atendimento->refresh();
    }

    public function render()
    {
        $this->atendimento->load(['cliente', 'responsavel', 'processo']);

        return view('livewire.atendimentos.atendimento-show');
    }
}
