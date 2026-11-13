<?php

namespace App\Livewire\Clientes;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ClienteForm extends Component
{
    public ?Cliente $cliente = null;

    public string $tipo = 'fisica';
    public string $nome = '';
    public ?string $cpf_cnpj = null;
    public ?string $rg = null;
    public ?string $email = null;
    public ?string $telefone = null;
    public ?string $celular = null;
    public ?string $cep = null;
    public ?string $logradouro = null;
    public ?string $numero = null;
    public ?string $complemento = null;
    public ?string $bairro = null;
    public ?string $cidade = null;
    public ?string $uf = null;
    public ?string $observacoes = null;
    public bool $ativo = true;

    public function mount(?Cliente $cliente = null): void
    {
        if ($cliente && $cliente->exists) {
            abort_unless($cliente->escritorio_id === Auth::user()->escritorio_id, 403);
            $this->cliente = $cliente;
            $this->fill($cliente->only([
                'tipo', 'nome', 'cpf_cnpj', 'rg', 'email', 'telefone', 'celular',
                'cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf',
                'observacoes', 'ativo',
            ]));
        }
    }

    protected function rules(): array
    {
        return [
            'tipo'        => ['required', 'in:fisica,juridica'],
            'nome'        => ['required', 'string', 'max:255'],
            'cpf_cnpj'    => ['nullable', 'string', 'max:18'],
            'rg'          => ['nullable', 'string', 'max:20'],
            'email'       => ['nullable', 'email', 'max:255'],
            'telefone'    => ['nullable', 'string', 'max:20'],
            'celular'     => ['nullable', 'string', 'max:20'],
            'cep'         => ['nullable', 'string', 'max:9'],
            'logradouro'  => ['nullable', 'string', 'max:255'],
            'numero'      => ['nullable', 'string', 'max:20'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'bairro'      => ['nullable', 'string', 'max:255'],
            'cidade'      => ['nullable', 'string', 'max:255'],
            'uf'          => ['nullable', 'string', 'max:2'],
            'observacoes' => ['nullable', 'string'],
            'ativo'       => ['boolean'],
        ];
    }

    public function save()
    {
        $dados = $this->validate();
        $dados['escritorio_id'] = Auth::user()->escritorio_id;

        if ($this->cliente) {
            $this->cliente->update($dados);
            session()->flash('ok', 'Cliente atualizado.');
        } else {
            $this->cliente = Cliente::create($dados);
            session()->flash('ok', 'Cliente criado.');
        }

        return $this->redirectRoute('clientes.show', $this->cliente, navigate: true);
    }

    public function render()
    {
        return view('livewire.clientes.cliente-form');
    }
}
