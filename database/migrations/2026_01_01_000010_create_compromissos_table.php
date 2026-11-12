<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compromissos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('processo_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo');
            $table->string('tipo')->default('audiencia');
            $table->dateTime('inicio');
            $table->dateTime('fim')->nullable();
            $table->string('local')->nullable();
            $table->string('status')->default('agendado');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compromissos');
    }
};
