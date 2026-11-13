<?php

namespace App\Livewire\Publicacoes;

use App\Models\Processo;
use App\Models\Publicacao;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PublicacaoForm extends Component
{
    public ?Publicacao $publicacao = null;

    public ?int $processo_id = null;
    public string $data_publicacao = '';
    public ?string $diario = null;
    public ?string $numero_processo = null;
    public string $conteudo = '';
    public bool $lida = false;

    public function mount(?Publicacao $publicacao = null): void
    {
        if ($publicacao && $publicacao->exists) {
            abort_unless($publicacao->escritorio_id === Auth::user()->escritorio_id, 403);
            $this->publicacao = $publicacao;
            $this->fill($publicacao->only(['processo_id', 'diario', 'numero_processo', 'conteudo', 'lida']));
            $this->data_publicacao = optional($publicacao->data_publicacao)->format('Y-m-d');
        } else {
            $this->data_publicacao = now()->format('Y-m-d');
        }
    }

    protected function rules(): array
    {
        return [
            'processo_id'     => ['nullable', 'exists:processos,id'],
            'data_publicacao' => ['required', 'date'],
            'diario'          => ['nullable', 'string', 'max:255'],
            'numero_processo' => ['nullable', 'string', 'max:255'],
            'conteudo'        => ['required', 'string'],
            'lida'            => ['boolean'],
        ];
    }

    public function save()
    {
        $dados = $this->validate();
        $dados['escritorio_id'] = Auth::user()->escritorio_id;
        $dados['responsavel_id'] = Auth::id();

        if ($this->publicacao) {
            $this->publicacao->update($dados);
        } else {
            Publicacao::create($dados);
        }

        session()->flash('ok', 'Publicação salva.');

        return $this->redirectRoute('publicacoes.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.publicacoes.publicacao-form', [
            'processos' => Processo::where('escritorio_id', Auth::user()->escritorio_id)->orderByDesc('id')->get(),
        ]);
    }
}
