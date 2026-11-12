<?php

namespace App\Livewire\Processos;

use App\Models\Cliente;
use App\Models\Processo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ProcessoForm extends Component
{
    public ?Processo $processo = null;

    public ?int $cliente_id = null;
    public ?int $advogado_id = null;
    public ?string $numero_processo = null;
    public ?string $numero_pasta = null;
    public ?string $tipo_acao = null;
    public ?string $area = null;
    public ?string $tribunal = null;
    public ?string $comarca = null;
    public ?string $vara = null;
    public ?string $instancia = null;
    public ?string $valor_causa = null;
    public string $fase = 'inicial';
    public string $status = 'ativo';
    public ?string $data_distribuicao = null;
    public ?string $observacoes = null;

    public function mount(?Processo $processo = null): void
    {
        if ($processo && $processo->exists) {
            abort_unless($processo->escritorio_id === Auth::user()->escritorio_id, 403);
            $this->processo = $processo;
            $this->fill($processo->only([
                'cliente_id', 'advogado_id', 'numero_processo', 'numero_pasta', 'tipo_acao',
                'area', 'tribunal', 'comarca', 'vara', 'instancia', 'valor_causa',
                'fase', 'status', 'observacoes',
            ]));
            $this->data_distribuicao = optional($processo->data_distribuicao)->format('Y-m-d');
        }
    }

    protected function rules(): array
    {
        return [
            'cliente_id'        => ['required', 'exists:clientes,id'],
            'advogado_id'       => ['nullable', 'exists:users,id'],
            'numero_processo'   => ['nullable', 'string', 'max:255'],
            'numero_pasta'      => ['nullable', 'string', 'max:255'],
            'tipo_acao'         => ['nullable', 'string', 'max:255'],
            'area'              => ['nullable', 'string', 'max:255'],
            'tribunal'          => ['nullable', 'string', 'max:255'],
            'comarca'           => ['nullable', 'string', 'max:255'],
            'vara'              => ['nullable', 'string', 'max:255'],
            'instancia'         => ['nullable', 'string', 'max:255'],
            'valor_causa'       => ['nullable', 'numeric', 'min:0'],
            'fase'              => ['required', 'string'],
            'status'            => ['required', 'string'],
            'data_distribuicao' => ['nullable', 'date'],
            'observacoes'       => ['nullable', 'string'],
        ];
    }

    public function save()
    {
        $dados = $this->validate();
        $dados['escritorio_id'] = Auth::user()->escritorio_id;

        if ($this->processo) {
            $this->processo->update($dados);
            session()->flash('ok', 'Processo atualizado.');
        } else {
            $this->processo = Processo::create($dados);
            session()->flash('ok', 'Processo criado.');
        }

        return $this->redirectRoute('processos.show', $this->processo, navigate: true);
    }

    public function render()
    {
        $escritorioId = Auth::user()->escritorio_id;

        return view('livewire.processos.processo-form', [
            'clientes'  => Cliente::where('escritorio_id', $escritorioId)->orderBy('nome')->get(),
            'advogados' => User::where('escritorio_id', $escritorioId)->orderBy('name')->get(),
        ]);
    }
}
