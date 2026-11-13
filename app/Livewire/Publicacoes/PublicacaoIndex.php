<?php

namespace App\Livewire\Publicacoes;

use App\Models\Publicacao;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PublicacaoIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $busca = '';

    #[Url]
    public string $filtro = 'nao_lidas'; // nao_lidas, lidas, todas

    public function updating($name): void
    {
        if ($name !== 'page') {
            $this->resetPage();
        }
    }

    public function toggleLida(int $id): void
    {
        $pub = Publicacao::where('escritorio_id', Auth::user()->escritorio_id)->findOrFail($id);
        $pub->update(['lida' => ! $pub->lida]);
    }

    public function marcarTodasLidas(): void
    {
        Publicacao::where('escritorio_id', Auth::user()->escritorio_id)->where('lida', false)->update(['lida' => true]);
    }

    public function render()
    {
        $eid = Auth::user()->escritorio_id;

        $publicacoes = Publicacao::query()
            ->where('escritorio_id', $eid)
            ->with(['processo', 'responsavel'])
            ->when($this->filtro === 'nao_lidas', fn ($q) => $q->where('lida', false))
            ->when($this->filtro === 'lidas', fn ($q) => $q->where('lida', true))
            ->when($this->busca, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('conteudo', 'like', "%{$this->busca}%")
                        ->orWhere('numero_processo', 'like', "%{$this->busca}%")
                        ->orWhere('diario', 'like', "%{$this->busca}%");
                });
            })
            ->orderByDesc('data_publicacao')
            ->paginate(15);

        $naoLidas = Publicacao::where('escritorio_id', $eid)->where('lida', false)->count();

        return view('livewire.publicacoes.publicacao-index', compact('publicacoes', 'naoLidas'));
    }
}
