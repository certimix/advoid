<?php

namespace App\Livewire\Processos;

use App\Models\Processo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ProcessoIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $busca = '';

    #[Url]
    public string $status = 'ativo';

    #[Url]
    public string $area = '';

    #[Url]
    public string $instancia = '';

    #[Url]
    public string $tribunal = '';

    public function updating($name): void
    {
        if ($name !== 'page') {
            $this->resetPage();
        }
    }

    public function limparFiltros(): void
    {
        $this->reset(['busca', 'area', 'instancia', 'tribunal']);
        $this->status = 'todos';
        $this->resetPage();
    }

    public function render()
    {
        $escritorioId = Auth::user()->escritorio_id;

        $processos = Processo::query()
            ->where('escritorio_id', $escritorioId)
            ->with(['cliente', 'advogado'])
            ->when($this->status !== 'todos', fn ($q) => $q->where('status', $this->status))
            ->when($this->area, fn ($q) => $q->where('area', $this->area))
            ->when($this->instancia, fn ($q) => $q->where('instancia', $this->instancia))
            ->when($this->tribunal, fn ($q) => $q->where('tribunal', 'like', "%{$this->tribunal}%"))
            ->when($this->busca, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('numero_processo', 'like', "%{$this->busca}%")
                        ->orWhere('numero_pasta', 'like', "%{$this->busca}%")
                        ->orWhere('tipo_acao', 'like', "%{$this->busca}%")
                        ->orWhereHas('cliente', fn ($c) => $c->where('nome', 'like', "%{$this->busca}%"))
                        ->orWhereHas('partes', fn ($p) => $p->where('nome', 'like', "%{$this->busca}%"));
                });
            })
            ->latest()
            ->paginate(15);

        $base = Processo::where('escritorio_id', $escritorioId);
        $contadores = [
            'todos'     => (clone $base)->count(),
            'ativo'     => (clone $base)->where('status', 'ativo')->count(),
            'arquivado' => (clone $base)->where('status', 'arquivado')->count(),
            'suspenso'  => (clone $base)->where('status', 'suspenso')->count(),
        ];

        return view('livewire.processos.processo-index', compact('processos', 'contadores'));
    }
}
