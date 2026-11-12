<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Escritorio extends Model
{
    protected $table = 'escritorios';

    protected $fillable = [
        'nome', 'cnpj', 'oab_responsavel', 'email', 'telefone',
        'cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf',
        'logo_path', 'ativo',
    ];

    protected $casts = ['ativo' => 'boolean'];

    public function usuarios(): HasMany { return $this->hasMany(User::class); }
    public function clientes(): HasMany { return $this->hasMany(Cliente::class); }
    public function processos(): HasMany { return $this->hasMany(Processo::class); }
    public function prazos(): HasMany { return $this->hasMany(Prazo::class); }
}
