<?php

namespace App\Livewire\Processos;

use App\Models\FaseProcesso;
use App\Models\Processo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class ProcessoKanban extends Component
{
    #[Url]
    public string $quadro = 'judicial'; // judicial, administrativo, arquivamento

    public function mover($processoId, $faseId = null): void
    {
        Processo::where('escritorio_id', Auth::user()->escritorio_id)
            ->whereKey($processoId)
            ->update(['fase_id' => $faseId ?: null]);
    }

    public function criarFasesPadrao(): void
    {
        $eid = Auth::user()->escritorio_id;

        $defaults = [
            'judicial'       => ['Ação Protocolada', 'Com Juiz para Análise', 'Aguardando Manifestação', 'Em Recurso', 'Sentença'],
            'administrativo' => ['Protocolado', 'Em Análise', 'Em Exigência', 'Concluído'],
            'arquivamento'   => ['A Arquivar', 'Arquivado'],
        ][$this->quadro] ?? [];

        foreach ($defaults as $i => $nome) {
            FaseProcesso::firstOrCreate(
                ['escritorio_id' => $eid, 'quadro' => $this->quadro, 'nome' => $nome],
                ['ordem' => $i]
            );
        }
    }

    public function render()
    {
        $eid = Auth::user()->escritorio_id;

        $fases = FaseProcesso::where('escritorio_id', $eid)
            ->where('quadro', $this->quadro)
            ->orderBy('ordem')
            ->get();

        $faseIds = $fases->pluck('id');

        $processos = Processo::where('escritorio_id', $eid)
            ->where('status', '!=', 'arquivado')
            ->where(fn ($q) => $q->whereNull('fase_id')->orWhereIn('fase_id', $faseIds))
            ->with('cliente')
            ->get();

        return view('livewire.processos.processo-kanban', [
            'fases'    => $fases,
            'porFase'  => $processos->groupBy('fase_id'),
            'semFase'  => $processos->whereNull('fase_id'),
        ]);
    }
}
