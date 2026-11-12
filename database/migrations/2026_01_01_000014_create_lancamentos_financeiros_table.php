<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lancamentos_financeiros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('processo_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('atendimento_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias_financeiras')->nullOnDelete();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipo')->default('receita');
            $table->string('descricao');
            $table->decimal('valor', 15, 2)->default(0);
            $table->decimal('valor_pago', 15, 2)->default(0);
            $table->date('vencimento')->nullable();
            $table->date('pago_em')->nullable();
            $table->string('status')->default('pendente');
            $table->string('forma_pagamento')->nullable();
            $table->string('comprovante_path')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lancamentos_financeiros');
    }
};
