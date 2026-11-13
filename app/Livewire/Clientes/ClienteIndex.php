<?php

namespace App\Livewire\Clientes;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ClienteIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $busca = '';

    #[Url]
    public string $tipo = '';

    #[Url]
    public string $ativo = '1';

    public function updating($name): void
    {
        if ($name !== 'page') {
            $this->resetPage();
        }
    }

    public function limparFiltros(): void
    {
        $this->reset(['busca', 'tipo']);
        $this->ativo = '';
        $this->resetPage();
    }

    public function render()
    {
        $eid = Auth::user()->escritorio_id;

        $clientes = Cliente::query()
            ->where('escritorio_id', $eid)
            ->withCount('processos')
            ->when($this->tipo, fn ($q) => $q->where('tipo', $this->tipo))
            ->when($this->ativo !== '', fn ($q) => $q->where('ativo', $this->ativo))
            ->when($this->busca, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nome', 'like', "%{$this->busca}%")
                        ->orWhere('cpf_cnpj', 'like', "%{$this->busca}%")
                        ->orWhere('email', 'like', "%{$this->busca}%");
                });
            })
            ->orderBy('nome')
            ->paginate(15);

        return view('livewire.clientes.cliente-index', compact('clientes'));
    }
}
