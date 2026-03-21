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
        Schema::create('config', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key', 64);
            $table->string('subkey', 64);
            $table->string('value',36)->nullable();
            $table->uuid('tp_dominio_id')->index()->nullable();
            $table->foreign('tp_dominio_id')->references('id')->on('tp_dominio')->onDelete('cascade');
            $table->uuid('dominio_id')->index()->nullable();
            $table->foreign('dominio_id')->references('id')->on('dominio')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config');
    }
};
