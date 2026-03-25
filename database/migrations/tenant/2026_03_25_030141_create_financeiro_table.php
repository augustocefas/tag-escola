<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financeiro', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('aluno_id')->index();
            $table->date('emissao');
            $table->date('vencimento');
            $table->date('pagamento')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('desconto', 10, 2)->nullable();
            $table->decimal('juros', 10, 2)->nullable();
            $table->decimal('mora', 10, 2)->nullable();
            $table->decimal('total', 10, 2);
            $table->string('obs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financeiro');
    }
};
