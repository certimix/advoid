<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias_financeiras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained()->cascadeOnDelete();
            $table->string('nome');
            $table->string('tipo')->default('receita');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias_financeiras');
    }
};
