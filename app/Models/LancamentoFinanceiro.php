<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LancamentoFinanceiro extends Model
{
    protected $table = 'lancamentos_financeiros';

    protected $fillable = [
        'escritorio_id', 'cliente_id', 'processo_id', 'atendimento_id', 'categoria_id',
        'responsavel_id', 'tipo', 'descricao', 'valor', 'valor_pago',
        'vencimento', 'pago_em', 'status', 'forma_pagamento', 'comprovante_path', 'observacoes',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'valor_pago' => 'decimal:2',
        'vencimento' => 'date',
        'pago_em' => 'date',
    ];

    public function escritorio(): BelongsTo { return $this->belongsTo(Escritorio::class); }
    public function cliente(): BelongsTo { return $this->belongsTo(Cliente::class); }
    public function processo(): BelongsTo { return $this->belongsTo(Processo::class); }
    public function atendimento(): BelongsTo { return $this->belongsTo(Atendimento::class); }
    public function categoria(): BelongsTo { return $this->belongsTo(CategoriaFinanceira::class, 'categoria_id'); }
    public function responsavel(): BelongsTo { return $this->belongsTo(User::class, 'responsavel_id'); }
}
