<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModeloPeca extends Model
{
    protected $table = 'modelos_peca';

    protected $fillable = [
        'escritorio_id', 'created_by', 'titulo', 'area', 'tipo_peca',
        'categoria', 'conteudo', 'status',
    ];

    public function escritorio(): BelongsTo { return $this->belongsTo(Escritorio::class); }
    public function autor(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}
