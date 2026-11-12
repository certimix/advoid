<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaseProcesso extends Model
{
    protected $table = 'fases_processo';

    protected $fillable = ['escritorio_id', 'nome', 'quadro', 'ordem', 'cor'];

    protected $casts = ['ordem' => 'integer'];

    public function escritorio(): BelongsTo { return $this->belongsTo(Escritorio::class); }
    public function processos(): HasMany { return $this->hasMany(Processo::class, 'fase_id'); }
}
