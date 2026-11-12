<?php

namespace App\Livewire\Processos;

use App\Models\Processo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ProcessoShow extends Component
{
    public Processo $processo;
    public string $aba = 'dados';

    public string $parte_tipo = 'reu';
    public string $parte_nome = '';
    public ?string $parte_cpf_cnpj = null;
    public ?string $parte_advogado_adverso = null;

    public string $mov_data = '';
    public string $mov_tipo = 'andamento';
    public string $mov_descricao = '';

    public string $prazo_titulo = '';
    public string $prazo_data = '';
    public ?string $prazo_fatal = null;
    public string $prazo_prioridade = 'media';

    public function mount(Processo $processo): void
    {
        abort_unless($processo->escritorio_id === Auth::user()->escritorio_id, 403);
        $this->processo = $processo;
        $this->mov_data = now()->format('Y-m-d');
    }

    public function addParte(): void
    {
        $this->validate([
            'parte_nome' => ['required', 'string', 'max:255'],
            'parte_tipo' => ['required', 'string'],
        ]);

        $this->processo->partes()->create([
            'tipo'             => $this->parte_tipo,
            'nome'             => $this->parte_nome,
            'cpf_cnpj'         => $this->parte_cpf_cnpj,
            'advogado_adverso' => $this->parte_advogado_adverso,
        ]);

        $this->reset(['parte_nome', 'parte_cpf_cnpj', 'parte_advogado_adverso']);
        $this->parte_tipo = 'reu';
        $this->processo->refresh();
    }

    public function removerParte(int $id): void
    {
        $this->processo->partes()->whereKey($id)->delete();
        $this->processo->refresh();
    }

    public function addMovimentacao(): void
    {
        $this->validate([
            'mov_data'      => ['required', 'date'],
            'mov_descricao' => ['required', 'string'],
            'mov_tipo'      => ['required', 'string'],
        ]);

        $this->processo->movimentacoes()->create([
            'data'       => $this->mov_data,
            'tipo'       => $this->mov_tipo,
            'descricao'  => $this->mov_descricao,
            'origem'     => 'manual',
            'created_by' => Auth::id(),
        ]);

        $this->reset(['mov_descricao']);
        $this->mov_tipo = 'andamento';
        $this->mov_data = now()->format('Y-m-d');
        $this->processo->refresh();
    }

    public function addPrazo(): void
    {
        $this->validate([
            'prazo_titulo' => ['required', 'string', 'max:255'],
            'prazo_data'   => ['required', 'date'],
            'prazo_fatal'  => ['nullable', 'date'],
        ]);

        $this->processo->prazos()->create([
            'escritorio_id'  => $this->processo->escritorio_id,
            'responsavel_id' => $this->processo->advogado_id,
            'titulo'         => $this->prazo_titulo,
            'data_prazo'     => $this->prazo_data,
            'data_fatal'     => $this->prazo_fatal,
            'prioridade'     => $this->prazo_prioridade,
            'status'         => 'pendente',
        ]);

        $this->reset(['prazo_titulo', 'prazo_data', 'prazo_fatal']);
        $this->prazo_prioridade = 'media';
        $this->processo->refresh();
    }

    public function concluirPrazo(int $id): void
    {
        $this->processo->prazos()->whereKey($id)->update([
            'status'       => 'cumprido',
            'concluido_em' => now(),
        ]);
        $this->processo->refresh();
    }

    public function render()
    {
        $this->processo->load([
            'cliente', 'advogado', 'fase', 'partes', 'movimentacoes',
            'prazos' => fn ($q) => $q->orderByRaw("FIELD(status,'pendente','atrasado','cumprido','cancelado')")->orderBy('data_prazo'),
            'lancamentos' => fn ($q) => $q->latest('vencimento'),
        ]);

        return view('livewire.processos.processo-show');
    }
}
