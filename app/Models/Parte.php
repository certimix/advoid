<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parte extends Model
{
    protected $table = 'partes';

    protected $fillable = [
        'processo_id', 'tipo', 'nome', 'cpf_cnpj', 'qualificacao', 'advogado_adverso',
    ];

    public function processo(): BelongsTo { return $this->belongsTo(Processo::class); }
}
