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
        Schema::create('sala', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tp_dominio_turno_id')->index();
            $table->foreign('tp_dominio_turno_id')->references('id')->on('dominio')->onDelete('cascade');
            $table->uuid('tp_dominio_periodo_id')->index()->nullable();
            $table->foreign('tp_dominio_periodo_id')->references('id')->on('dominio')->onDelete('cascade');
            $table->year('ano')->index()->nullable();
            $table->string('nome',128)->nullable();
            $table->string('sigla',64)->nullable();
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
        Schema::dropIfExists('sala');
    }
};
