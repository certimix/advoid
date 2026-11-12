<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('escritorio_id')->nullable()->after('id')
                  ->constrained('escritorios')->nullOnDelete();
            $table->string('oab')->nullable()->after('email');
            $table->string('telefone', 20)->nullable()->after('oab');
            $table->string('cargo')->default('advogado')->after('telefone');
            $table->boolean('ativo')->default(true)->after('cargo');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('escritorio_id');
            $table->dropColumn(['oab', 'telefone', 'cargo', 'ativo']);
        });
    }
};
