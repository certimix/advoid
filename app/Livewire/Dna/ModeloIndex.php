<?php

namespace App\Livewire\Dna;

use App\Models\ModeloPeca;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ModeloIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $busca = '';

    #[Url]
    public string $categoria = '';

    #[Url]
    public string $area = '';

    #[Url]
    public string $status = '';

    public function updating($name): void
    {
        if ($name !== 'page') {
            $this->resetPage();
        }
    }

    public function excluir(int $id): void
    {
        ModeloPeca::where('escritorio_id', Auth::user()->escritorio_id)->whereKey($id)->delete();
    }

    public function limparFiltros(): void
    {
        $this->reset(['busca', 'categoria', 'area', 'status']);
        $this->resetPage();
    }

    public function render()
    {
        $eid = Auth::user()->escritorio_id;

        $modelos = ModeloPeca::query()
            ->where('escritorio_id', $eid)
            ->when($this->categoria, fn ($q) => $q->where('categoria', $this->categoria))
            ->when($this->area, fn ($q) => $q->where('area', $this->area))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->busca, fn ($q) => $q->where('titulo', 'like', "%{$this->busca}%"))
            ->latest()
            ->paginate(12);

        return view('livewire.dna.modelo-index', compact('modelos'));
    }
}
