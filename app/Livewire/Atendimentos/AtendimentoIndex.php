<?php

namespace App\Livewire\Atendimentos;

use App\Models\Atendimento;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AtendimentoIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $busca = '';

    #[Url]
    public string $status = 'em_andamento';

    #[Url]
    public string $area = '';

    #[Url]
    public string $tipo = '';

    public function updating($name): void
    {
        if ($name !== 'page') {
            $this->resetPage();
        }
    }

    public function limparFiltros(): void
    {
        $this->reset(['busca', 'area', 'tipo']);
        $this->status = 'todos';
        $this->resetPage();
    }

    public function render()
    {
        $escritorioId = Auth::user()->escritorio_id;

        $atendimentos = Atendimento::query()
            ->where('escritorio_id', $escritorioId)
            ->with(['cliente', 'responsavel', 'processo'])
            ->when($this->status !== 'todos', fn ($q) => $q->where('status', $this->status))
            ->when($this->area, fn ($q) => $q->where('area', $this->area))
            ->when($this->tipo, fn ($q) => $q->where('tipo', $this->tipo))
            ->when($this->busca, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('assunto', 'like', "%{$this->busca}%")
                        ->orWhere('descricao', 'like', "%{$this->busca}%")
                        ->orWhereHas('cliente', fn ($c) => $c->where('nome', 'like', "%{$this->busca}%"));
                });
            })
            ->latest()
            ->paginate(15);

        $base = Atendimento::where('escritorio_id', $escritorioId);
        $contadores = [
            'todos'          => (clone $base)->count(),
            'em_andamento'   => (clone $base)->where('status', 'em_andamento')->count(),
            'triagem'        => (clone $base)->where('status', 'triagem')->count(),
            'virou_processo' => (clone $base)->where('status', 'virou_processo')->count(),
            'encerrado'      => (clone $base)->where('status', 'encerrado')->count(),
        ];

        return view('livewire.atendimentos.atendimento-index', compact('atendimentos', 'contadores'));
    }
}
