<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimentacao extends Model
{
    protected $table = 'movimentacoes';

    protected $fillable = [
        'processo_id', 'created_by', 'data', 'tipo', 'descricao', 'origem',
    ];

    protected $casts = ['data' => 'date'];

    public function processo(): BelongsTo { return $this->belongsTo(Processo::class); }
    public function autor(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}
