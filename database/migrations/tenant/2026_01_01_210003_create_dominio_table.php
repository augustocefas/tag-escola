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
        Schema::create('dominio', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tp_dominio_id')->nullable()->index();
            $table->foreign('tp_dominio_id')->references('id')->on('tp_dominio')->onDelete('cascade')->onUpdate('cascade');
            $table->string('dominio', 128);
            $table->uuid('anexo_id')->nullable()->index();
            $table->foreign('anexo_id')->references('id')->on('anexo')->onDelete('set null')->onUpdate('cascade');
            $table->unique(['tp_dominio_id', 'dominio']);
            $table->string('navegacao', 64)->nullable();
            $table->string('subnavegacao', 64)->nullable();
            $table->string('rota', 128)->nullable();
            $table->boolean('publico')->default(true);
            $table->boolean('datasource')->default(true);
            $table->string('icone', 32)->nullable();
            $table->string('fonte_cor', 32)->nullable();
            $table->string('fundo_cor', 32)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dominio');
    }
};
