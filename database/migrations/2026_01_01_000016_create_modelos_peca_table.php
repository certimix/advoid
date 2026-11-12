<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modelos_peca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo');
            $table->string('area')->nullable();
            $table->string('tipo_peca')->nullable();
            $table->string('categoria')->default('banco_blocos');
            $table->longText('conteudo')->nullable();
            $table->string('status')->default('ativo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modelos_peca');
    }
};
