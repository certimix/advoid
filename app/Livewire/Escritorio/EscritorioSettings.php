<?php

namespace App\Livewire\Escritorio;

use App\Models\Escritorio;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class EscritorioSettings extends Component
{
    use WithFileUploads;

    public Escritorio $escritorio;

    public string $nome = '';
    public ?string $cnpj = null;
    public ?string $oab_responsavel = null;
    public ?string $email = null;
    public ?string $telefone = null;
    public ?string $cep = null;
    public ?string $logradouro = null;
    public ?string $numero = null;
    public ?string $complemento = null;
    public ?string $bairro = null;
    public ?string $cidade = null;
    public ?string $uf = null;

    public $logo = null; // upload temporário

    public function mount(): void
    {
        $this->escritorio = Escritorio::findOrFail(Auth::user()->escritorio_id);
        $this->fill($this->escritorio->only([
            'nome', 'cnpj', 'oab_responsavel', 'email', 'telefone',
            'cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf',
        ]));
    }

    protected function rules(): array
    {
        return [
            'nome'            => ['required', 'string', 'max:255'],
            'cnpj'            => ['nullable', 'string', 'max:18'],
            'oab_responsavel' => ['nullable', 'string', 'max:255'],
            'email'           => ['nullable', 'email', 'max:255'],
            'telefone'        => ['nullable', 'string', 'max:20'],
            'cep'             => ['nullable', 'string', 'max:9'],
            'logradouro'      => ['nullable', 'string', 'max:255'],
            'numero'          => ['nullable', 'string', 'max:20'],
            'complemento'     => ['nullable', 'string', 'max:255'],
            'bairro'          => ['nullable', 'string', 'max:255'],
            'cidade'          => ['nullable', 'string', 'max:255'],
            'uf'              => ['nullable', 'string', 'max:2'],
            'logo'            => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $dados = $this->validate();

        if ($this->logo) {
            $dados['logo_path'] = $this->logo->store('logos', 'public');
        }
        unset($dados['logo']);

        $this->escritorio->update($dados);
        $this->logo = null;

        session()->flash('ok', 'Dados do escritório atualizados.');
    }

    public function render()
    {
        return view('livewire.escritorio.escritorio-settings');
    }
}
