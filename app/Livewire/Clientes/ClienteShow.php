<?php

namespace App\Livewire\Clientes;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ClienteShow extends Component
{
    public Cliente $cliente;

    public function mount(Cliente $cliente): void
    {
        abort_unless($cliente->escritorio_id === Auth::user()->escritorio_id, 403);
        $this->cliente = $cliente;
    }

    public function render()
    {
        $this->cliente->load([
            'processos' => fn ($q) => $q->latest(),
            'atendimentos' => fn ($q) => $q->latest(),
        ]);

        return view('livewire.clientes.cliente-show');
    }
}
