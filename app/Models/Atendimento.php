<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Atendimento extends Model
{
    protected $table = 'atendimentos';

    protected $fillable = [
        'escritorio_id', 'cliente_id', 'responsavel_id', 'processo_id',
        'assunto', 'area', 'tipo', 'origem', 'status', 'descricao',
    ];

    public function escritorio(): BelongsTo { return $this->belongsTo(Escritorio::class); }
    public function cliente(): BelongsTo { return $this->belongsTo(Cliente::class); }
    public function responsavel(): BelongsTo { return $this->belongsTo(User::class, 'responsavel_id'); }
    public function processo(): BelongsTo { return $this->belongsTo(Processo::class); }
    public function lancamentos(): HasMany { return $this->hasMany(LancamentoFinanceiro::class); }
}
