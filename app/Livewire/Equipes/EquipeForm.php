<?php

namespace App\Livewire\Equipes;

use App\Models\Equipe;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EquipeForm extends Component
{
    public ?Equipe $equipe = null;

    public string $nome = '';
    public ?string $descricao = null;
    public array $membros = [];

    public function mount(?Equipe $equipe = null): void
    {
        if ($equipe && $equipe->exists) {
            abort_unless($equipe->escritorio_id === Auth::user()->escritorio_id, 403);
            $this->equipe = $equipe;
            $this->nome = $equipe->nome;
            $this->descricao = $equipe->descricao;
            $this->membros = $equipe->membros->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'nome'      => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'membros'   => ['array'],
            'membros.*' => ['exists:users,id'],
        ];
    }

    public function save()
    {
        $dados = $this->validate();
        $eid = Auth::user()->escritorio_id;

        if ($this->equipe) {
            $this->equipe->update(['nome' => $dados['nome'], 'descricao' => $dados['descricao']]);
        } else {
            $this->equipe = Equipe::create([
                'escritorio_id' => $eid,
                'nome'          => $dados['nome'],
                'descricao'     => $dados['descricao'],
            ]);
        }

        $this->equipe->membros()->sync($dados['membros']);

        session()->flash('ok', 'Equipe salva.');

        return $this->redirectRoute('equipes.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.equipes.equipe-form', [
            'usuarios' => User::where('escritorio_id', Auth::user()->escritorio_id)->orderBy('name')->get(),
        ]);
    }
}
