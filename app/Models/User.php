<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'escritorio_id', 'name', 'email', 'password',
        'oab', 'telefone', 'cargo', 'ativo',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ativo' => 'boolean',
        ];
    }

    public function escritorio(): BelongsTo { return $this->belongsTo(Escritorio::class); }
    public function processos(): HasMany { return $this->hasMany(Processo::class, 'advogado_id'); }
    public function prazos(): HasMany { return $this->hasMany(Prazo::class, 'responsavel_id'); }

    public function equipes(): BelongsToMany
    {
        return $this->belongsToMany(Equipe::class, 'equipe_user')->withPivot('papel')->withTimestamps();
    }
}
