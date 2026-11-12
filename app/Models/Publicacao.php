<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publicacao extends Model
{
    protected $table = 'publicacoes';

    protected $fillable = [
        'escritorio_id', 'processo_id', 'responsavel_id',
        'data_publicacao', 'diario', 'numero_processo', 'conteudo', 'lida',
    ];

    protected $casts = [
        'data_publicacao' => 'date',
        'lida' => 'boolean',
    ];

    public function escritorio(): BelongsTo { return $this->belongsTo(Escritorio::class); }
    public function processo(): BelongsTo { return $this->belongsTo(Processo::class); }
    public function responsavel(): BelongsTo { return $this->belongsTo(User::class, 'responsavel_id'); }
}
