<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prazo extends Model
{
    protected $fillable = [
        'escritorio_id', 'processo_id', 'responsavel_id', 'titulo', 'descricao',
        'data_prazo', 'data_fatal', 'tipo', 'prioridade', 'status', 'concluido_em',
    ];

    protected $casts = [
        'data_prazo' => 'date',
        'data_fatal' => 'date',
        'concluido_em' => 'datetime',
    ];

    public function escritorio(): BelongsTo { return $this->belongsTo(Escritorio::class); }
    public function processo(): BelongsTo { return $this->belongsTo(Processo::class); }
    public function responsavel(): BelongsTo { return $this->belongsTo(User::class, 'responsavel_id'); }
}
