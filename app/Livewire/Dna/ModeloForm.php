<?php

namespace App\Livewire\Dna;

use App\Models\ModeloPeca;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ModeloForm extends Component
{
    public ?ModeloPeca $modelo = null;

    public string $titulo = '';
    public ?string $area = null;
    public ?string $tipo_peca = null;
    public string $categoria = 'banco_blocos';
    public string $status = 'ativo';
    public ?string $conteudo = null;

    public function mount(?ModeloPeca $modelo = null): void
    {
        if ($modelo && $modelo->exists) {
            abort_unless($modelo->escritorio_id === Auth::user()->escritorio_id, 403);
            $this->modelo = $modelo;
            $this->fill($modelo->only(['titulo', 'area', 'tipo_peca', 'categoria', 'status', 'conteudo']));
        }
    }

    protected function rules(): array
    {
        return [
            'titulo'    => ['required', 'string', 'max:255'],
            'area'      => ['nullable', 'string', 'max:255'],
            'tipo_peca' => ['nullable', 'string', 'max:255'],
            'categoria' => ['required', 'string'],
            'status'    => ['required', 'string'],
            'conteudo'  => ['nullable', 'string'],
        ];
    }

    public function save()
    {
        $dados = $this->validate();
        $dados['escritorio_id'] = Auth::user()->escritorio_id;
        $dados['created_by'] = Auth::id();

        if ($this->modelo) {
            $this->modelo->update($dados);
        } else {
            ModeloPeca::create($dados);
        }

        session()->flash('ok', 'Bloco/peça salvo.');

        return $this->redirectRoute('dna.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.dna.modelo-form');
    }
}
