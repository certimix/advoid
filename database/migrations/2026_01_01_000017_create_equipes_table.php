<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained()->cascadeOnDelete();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        Schema::create('equipe_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('papel')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipe_user');
        Schema::dropIfExists('equipes');
    }
};
