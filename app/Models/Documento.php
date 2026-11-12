<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    protected $table = 'documentos';

    protected $fillable = [
        'escritorio_id', 'processo_id', 'atendimento_id', 'created_by',
        'nome', 'tipo', 'arquivo_path', 'tamanho',
    ];

    protected $casts = ['tamanho' => 'integer'];

    public function escritorio(): BelongsTo { return $this->belongsTo(Escritorio::class); }
    public function processo(): BelongsTo { return $this->belongsTo(Processo::class); }
    public function atendimento(): BelongsTo { return $this->belongsTo(Atendimento::class); }
    public function autor(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}
