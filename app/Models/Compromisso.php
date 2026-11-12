<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Compromisso extends Model
{
    protected $table = 'compromissos';

    protected $fillable = [
        'escritorio_id', 'processo_id', 'responsavel_id', 'titulo', 'tipo',
        'inicio', 'fim', 'local', 'status', 'observacoes',
    ];

    protected $casts = [
        'inicio' => 'datetime',
        'fim' => 'datetime',
    ];

    public function escritorio(): BelongsTo { return $this->belongsTo(Escritorio::class); }
    public function processo(): BelongsTo { return $this->belongsTo(Processo::class); }
    public function responsavel(): BelongsTo { return $this->belongsTo(User::class, 'responsavel_id'); }
}
