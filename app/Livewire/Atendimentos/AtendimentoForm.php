<?php

namespace App\Livewire\Atendimentos;

use App\Models\Atendimento;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AtendimentoForm extends Component
{
    public ?Atendimento $atendimento = null;

    public ?int $cliente_id = null;
    public ?int $responsavel_id = null;
    public string $assunto = '';
    public ?string $area = null;
    public ?string $tipo = null;
    public ?string $origem = null;
    public string $status = 'em_andamento';
    public ?string $descricao = null;

    public function mount(?Atendimento $atendimento = null): void
    {
        if ($atendimento && $atendimento->exists) {
            abort_unless($atendimento->escritorio_id === Auth::user()->escritorio_id, 403);
            $this->atendimento = $atendimento;
            $this->fill($atendimento->only([
                'cliente_id', 'responsavel_id', 'assunto', 'area', 'tipo', 'origem', 'status', 'descricao',
            ]));
        } else {
            $this->responsavel_id = Auth::id();
        }
    }

    protected function rules(): array
    {
        return [
            'cliente_id'     => ['nullable', 'exists:clientes,id'],
            'responsavel_id' => ['nullable', 'exists:users,id'],
            'assunto'        => ['required', 'string', 'max:255'],
            'area'           => ['nullable', 'string', 'max:255'],
            'tipo'           => ['nullable', 'string', 'max:255'],
            'origem'         => ['nullable', 'string', 'max:255'],
            'status'         => ['required', 'string'],
            'descricao'      => ['nullable', 'string'],
        ];
    }

    public function save()
    {
        $dados = $this->validate();
        $dados['escritorio_id'] = Auth::user()->escritorio_id;

        if ($this->atendimento) {
            $this->atendimento->update($dados);
            session()->flash('ok', 'Atendimento atualizado.');
        } else {
            $this->atendimento = Atendimento::create($dados);
            session()->flash('ok', 'Atendimento criado.');
        }

        return $this->redirectRoute('atendimentos.show', $this->atendimento, navigate: true);
    }

    public function render()
    {
        $escritorioId = Auth::user()->escritorio_id;

        return view('livewire.atendimentos.atendimento-form', [
            'clientes'     => Cliente::where('escritorio_id', $escritorioId)->orderBy('nome')->get(),
            'responsaveis' => User::where('escritorio_id', $escritorioId)->orderBy('name')->get(),
        ]);
    }
}
