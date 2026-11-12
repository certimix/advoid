<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prazos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('processo_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->date('data_prazo');
            $table->date('data_fatal')->nullable();
            $table->string('tipo')->nullable();
            $table->string('prioridade')->default('media');
            $table->string('status')->default('pendente');
            $table->timestamp('concluido_em')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prazos');
    }
};
