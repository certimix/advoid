<?php

namespace App\Livewire\Equipes;

use App\Models\Equipe;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EquipeIndex extends Component
{
    public function excluir(int $id): void
    {
        Equipe::where('escritorio_id', Auth::user()->escritorio_id)->whereKey($id)->delete();
    }

    public function render()
    {
        $eid = Auth::user()->escritorio_id;

        $equipes = Equipe::where('escritorio_id', $eid)->withCount('membros')->with('membros')->orderBy('nome')->get();

        return view('livewire.equipes.equipe-index', compact('equipes'));
    }
}
