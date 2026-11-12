<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->constrained()->cascadeOnDelete();
            $table->string('tipo')->default('reu');
            $table->string('nome');
            $table->string('cpf_cnpj', 18)->nullable();
            $table->string('qualificacao')->nullable();
            $table->string('advogado_adverso')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partes');
    }
};
