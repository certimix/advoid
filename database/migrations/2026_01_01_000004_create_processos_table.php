<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('processos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained()->cascadeOnDelete();
            $table->foreignId('advogado_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('numero_processo')->nullable();
            $table->string('numero_pasta')->nullable();
            $table->string('tipo_acao')->nullable();
            $table->string('area')->nullable();
            $table->string('tribunal')->nullable();
            $table->string('comarca')->nullable();
            $table->string('vara')->nullable();
            $table->string('instancia')->nullable();
            $table->decimal('valor_causa', 15, 2)->nullable();
            $table->string('fase')->default('inicial');
            $table->string('status')->default('ativo');
            $table->date('data_distribuicao')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processos');
    }
};
