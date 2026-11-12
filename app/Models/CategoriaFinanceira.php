<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaFinanceira extends Model
{
    protected $table = 'categorias_financeiras';

    protected $fillable = ['escritorio_id', 'nome', 'tipo'];

    public function escritorio(): BelongsTo { return $this->belongsTo(Escritorio::class); }
    public function lancamentos(): HasMany { return $this->hasMany(LancamentoFinanceiro::class, 'categoria_id'); }
}
