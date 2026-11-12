<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Equipe extends Model
{
    protected $table = 'equipes';

    protected $fillable = ['escritorio_id', 'nome', 'descricao'];

    public function escritorio(): BelongsTo { return $this->belongsTo(Escritorio::class); }

    public function membros(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'equipe_user')->withPivot('papel')->withTimestamps();
    }
}
