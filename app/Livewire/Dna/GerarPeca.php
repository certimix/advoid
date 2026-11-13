<?php

namespace App\Livewire\Dna;

use App\Models\ModeloPeca;
use App\Models\Processo;
use App\Services\Ai\AiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class GerarPeca extends Component
{
    public ?int $processo_id = null;
    public array $blocos = [];
    public string $tipo_peca = '';
    public string $instrucoes = '';
    public string $resultado = '';
    public bool $iaDisponivel = false;
    public ?string $erro = null;

    public function mount(): void
    {
        $this->iaDisponivel = app(AiService::class)->disponivel();
    }

    public function gerar(): void
    {
        $this->erro = null;
        $this->validate([
            'instrucoes' => ['required', 'string'],
            'blocos'     => ['array'],
        ]);

        if (! $this->iaDisponivel) {
            $this->erro = 'IA não configurada. Defina AI_API_KEY no .env.';
            return;
        }

        $eid = Auth::user()->escritorio_id;
        $ctx = '';

        if ($this->processo_id) {
            $p = Processo::where('escritorio_id', $eid)->with(['cliente', 'partes'])->find($this->processo_id);
            if ($p) {
                $ctx .= "DADOS DO PROCESSO\n";
                $ctx .= 'Número: ' . ($p->numero_processo ?: '#' . $p->id) . "\n";
                $ctx .= 'Cliente: ' . ($p->cliente?->nome ?? '[PREENCHER]') . "\n";
                $ctx .= 'Área: ' . ($p->area ?? '—') . ' | Tribunal: ' . ($p->tribunal ?? '—') . ' | Vara: ' . ($p->vara ?? '—') . "\n";
                foreach ($p->partes as $parte) {
                    $ctx .= 'Parte (' . $parte->tipo . '): ' . $parte->nome . "\n";
                }
                $ctx .= "\n";
            }
        }

        if (! empty($this->blocos)) {
            $modelos = ModeloPeca::where('escritorio_id', $eid)->whereIn('id', $this->blocos)->get();
            if ($modelos->isNotEmpty()) {
                $ctx .= "BLOCOS / ESTILO DO ESCRITÓRIO\n";
                foreach ($modelos as $m) {
                    $ctx .= "### " . $m->titulo . "\n" . trim((string) $m->conteudo) . "\n\n";
                }
            }
        }

        if ($this->tipo_peca) {
            $this->instrucoes = "Redija uma {$this->tipo_peca}. " . $this->instrucoes;
        }

        try {
            $this->resultado = app(AiService::class)->gerarPeca($this->instrucoes, $ctx ?: 'Sem contexto adicional.');
        } catch (\Throwable $e) {
            $this->erro = 'Falha ao gerar: ' . $e->getMessage();
        }
    }

    public function salvarRascunho(): void
    {
        if (blank($this->resultado)) {
            return;
        }

        ModeloPeca::create([
            'escritorio_id' => Auth::user()->escritorio_id,
            'created_by'    => Auth::id(),
            'titulo'        => Str::limit($this->tipo_peca ?: $this->instrucoes, 60, ''),
            'tipo_peca'     => $this->tipo_peca ?: null,
            'categoria'     => 'sugestao',
            'status'        => 'rascunho',
            'conteudo'      => $this->resultado,
        ]);

        session()->flash('ok', 'Peça salva como rascunho no DNA.');
        $this->redirectRoute('dna.index', navigate: true);
    }

    public function render()
    {
        $eid = Auth::user()->escritorio_id;

        return view('livewire.dna.gerar-peca', [
            'processos' => Processo::where('escritorio_id', $eid)->orderByDesc('id')->get(),
            'modelos'   => ModeloPeca::where('escritorio_id', $eid)->orderBy('titulo')->get(),
        ]);
    }
}
