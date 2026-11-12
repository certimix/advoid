<?php

namespace App\Livewire\Agenda;

use App\Models\Compromisso;
use App\Models\Processo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CompromissoForm extends Component
{
    public ?Compromisso $compromisso = null;

    public ?int $processo_id = null;
    public ?int $responsavel_id = null;
    public string $titulo = '';
    public string $tipo = 'audiencia';
    public string $inicio = '';
    public ?string $fim = null;
    public ?string $local = null;
    public string $status = 'agendado';
    public ?string $observacoes = null;

    public function mount(?Compromisso $compromisso = null): void
    {
        if ($compromisso && $compromisso->exists) {
            abort_unless($compromisso->escritorio_id === Auth::user()->escritorio_id, 403);
            $this->compromisso = $compromisso;
            $this->fill($compromisso->only([
                'processo_id', 'responsavel_id', 'titulo', 'tipo', 'local', 'status', 'observacoes',
            ]));
            $this->inicio = $compromisso->inicio?->format('Y-m-d\TH:i');
            $this->fim = $compromisso->fim?->format('Y-m-d\TH:i');
        } else {
            $this->responsavel_id = Auth::id();
            $this->inicio = now()->addHour()->format('Y-m-d\TH:i');
        }
    }

    protected function rules(): array
    {
        return [
            'processo_id'    => ['nullable', 'exists:processos,id'],
            'responsavel_id' => ['nullable', 'exists:users,id'],
            'titulo'         => ['required', 'string', 'max:255'],
            'tipo'           => ['required', 'string'],
            'inicio'         => ['required', 'date'],
            'fim'            => ['nullable', 'date', 'after_or_equal:inicio'],
            'local'          => ['nullable', 'string', 'max:255'],
            'status'         => ['required', 'string'],
            'observacoes'    => ['nullable', 'string'],
        ];
    }

    public function save()
    {
        $dados = $this->validate();
        $dados['escritorio_id'] = Auth::user()->escritorio_id;

        if ($this->compromisso) {
            $this->compromisso->update($dados);
        } else {
            Compromisso::create($dados);
        }

        session()->flash('ok', 'Compromisso salvo.');

        return $this->redirectRoute('agenda.index', navigate: true);
    }

    public function render()
    {
        $escritorioId = Auth::user()->escritorio_id;

        return view('livewire.agenda.compromisso-form', [
            'processos'    => Processo::where('escritorio_id', $escritorioId)->orderByDesc('id')->get(),
            'responsaveis' => User::where('escritorio_id', $escritorioId)->orderBy('name')->get(),
        ]);
    }
}
