<?php

namespace App\Livewire;

use App\Models\Atendimento;
use App\Models\Compromisso;
use App\Models\Prazo;
use App\Models\Processo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $eid = Auth::user()->escritorio_id;

        return view('livewire.dashboard', [
            'processosAtivos'    => Processo::where('escritorio_id', $eid)->where('status', 'ativo')->count(),
            'prazosPendentes'    => Prazo::where('escritorio_id', $eid)->where('status', 'pendente')->count(),
            'atendimentosAbertos'=> Atendimento::where('escritorio_id', $eid)->where('status', 'em_andamento')->count(),
            'compromissosSemana' => Compromisso::where('escritorio_id', $eid)->where('status', 'agendado')
                                        ->whereBetween('inicio', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'proximos'           => Compromisso::where('escritorio_id', $eid)->where('status', 'agendado')
                                        ->where('inicio', '>=', now())->orderBy('inicio')->with('processo')->limit(6)->get(),
        ]);
    }
}
