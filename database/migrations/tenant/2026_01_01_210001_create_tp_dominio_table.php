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
        Schema::create('tp_dominio', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tp_dominio', 128)->unique();
            $table->string('navegacao', 64)->nullable();
            $table->string('subnavegacao', 64)->nullable();
            $table->string('rota', 128)->nullable();
            $table->boolean('publico')->default(true);
            $table->boolean('datasource')->default(true);
            $table->string('icone', 32)->nullable();
            $table->string('fonte_cor', 32)->nullable();
            $table->string('fundo_cor', 32)->nullable();
            $table->boolean('ativo')->default(true);
            $table->string('subtitulo',128)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_dominio');
    }
};
