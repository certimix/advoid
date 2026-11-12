<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('processos', function (Blueprint $table) {
            $table->foreignId('fase_id')->nullable()->after('fase')
                  ->constrained('fases_processo')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('processos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('fase_id');
        });
    }
};
