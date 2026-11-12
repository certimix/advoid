<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('processo_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('atendimento_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nome');
            $table->string('tipo')->nullable();
            $table->string('arquivo_path');
            $table->unsignedBigInteger('tamanho')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
