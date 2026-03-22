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
        Schema::create('aluno', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->date('nascimento')->nullable();
            $table->string('matricula')->unique()->nullable()->index();
            $table->uuid('anexo_id')->nullable()->index();
            $table->foreign('anexo_id')->references('id')->on('anexo')->onDelete('cascade');
            $table->json('dados_adicionais')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aluno');
    }
};
