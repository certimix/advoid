<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Processo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'escritorio_id', 'cliente_id', 'advogado_id', 'numero_processo', 'numero_pasta',
        'tipo_acao', 'area', 'tribunal', 'comarca', 'vara', 'instancia', 'valor_causa',
        'fase', 'fase_id', 'status', 'data_distribuicao', 'observacoes',
    ];

    protected $casts = [
        'valor_causa' => 'decimal:2',
        'data_distribuicao' => 'date',
    ];

    public function escritorio(): BelongsTo { return $this->belongsTo(Escritorio::class); }
    public function cliente(): BelongsTo { return $this->belongsTo(Cliente::class); }
    public function advogado(): BelongsTo { return $this->belongsTo(User::class, 'advogado_id'); }
    public function fase(): BelongsTo { return $this->belongsTo(FaseProcesso::class, 'fase_id'); }

    public function partes(): HasMany { return $this->hasMany(Parte::class); }
    public function movimentacoes(): HasMany { return $this->hasMany(Movimentacao::class)->latest('data'); }
    public function prazos(): HasMany { return $this->hasMany(Prazo::class); }
    public function compromissos(): HasMany { return $this->hasMany(Compromisso::class); }
    public function publicacoes(): HasMany { return $this->hasMany(Publicacao::class); }
    public function atendimentos(): HasMany { return $this->hasMany(Atendimento::class); }
    public function documentos(): HasMany { return $this->hasMany(Documento::class); }
    public function lancamentos(): HasMany { return $this->hasMany(LancamentoFinanceiro::class); }
}
