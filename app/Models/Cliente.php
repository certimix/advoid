<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'escritorio_id', 'tipo', 'nome', 'cpf_cnpj', 'rg', 'email', 'telefone', 'celular',
        'cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf',
        'observacoes', 'ativo',
    ];

    protected $casts = ['ativo' => 'boolean'];

    public function escritorio(): BelongsTo { return $this->belongsTo(Escritorio::class); }
    public function processos(): HasMany { return $this->hasMany(Processo::class); }
    public function atendimentos(): HasMany { return $this->hasMany(Atendimento::class); }
}
