<?php

namespace App\Livewire\Agenda;

use App\Models\Compromisso;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class AgendaIndex extends Component
{
    #[Url(as: 'q')]
    public string $busca = '';

    #[Url]
    public string $tipo = '';

    #[Url]
    public string $status = 'agendado';

    #[Url]
    public string $periodo = 'semana';

    public function marcarRealizado(int $id): void
    {
        Compromisso::where('escritorio_id', Auth::user()->escritorio_id)
            ->whereKey($id)->update(['status' => 'realizado']);
    }

    public function cancelar(int $id): void
    {
        Compromisso::where('escritorio_id', Auth::user()->escritorio_id)
            ->whereKey($id)->update(['status' => 'cancelado']);
    }

    public function limparFiltros(): void
    {
        $this->reset(['busca', 'tipo']);
        $this->status = 'agendado';
        $this->periodo = 'todos';
    }

    public function render()
    {
        $escritorioId = Auth::user()->escritorio_id;

        [$inicio, $fim] = match ($this->periodo) {
            'hoje'   => [now()->startOfDay(), now()->endOfDay()],
            'semana' => [now()->startOfWeek(), now()->endOfWeek()],
            'mes'    => [now()->startOfMonth(), now()->endOfMonth()],
            default  => [null, null],
        };

        $compromissos = Compromisso::query()
            ->where('escritorio_id', $escritorioId)
            ->with(['processo', 'responsavel'])
            ->when($this->status !== 'todos', fn ($q) => $q->where('status', $this->status))
            ->when($this->tipo, fn ($q) => $q->where('tipo', $this->tipo))
            ->when($inicio, fn ($q) => $q->whereBetween('inicio', [$inicio, $fim]))
            ->when($this->busca, fn ($q) => $q->where('titulo', 'like', "%{$this->busca}%"))
            ->orderBy('inicio')
            ->get()
            ->groupBy(fn ($c) => $c->inicio->format('Y-m-d'));

        $contagem = Compromisso::query()
            ->where('escritorio_id', $escritorioId)
            ->where('status', 'agendado')
            ->when($inicio, fn ($q) => $q->whereBetween('inicio', [$inicio, $fim]))
            ->selectRaw('tipo, count(*) as total')
            ->groupBy('tipo')
            ->pluck('total', 'tipo');

        return view('livewire.agenda.agenda-index', compact('compromissos', 'contagem'));
    }
}
