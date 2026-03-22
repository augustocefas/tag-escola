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
        Schema::create('tag', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('apelido', 128)->nullable();
            $table->string('mac_address', 17)->unique()->index()->nullable();
            $table->string('key', 64)->unique()->index()->nullable();
            $table->string('passkey', 64)->index()->nullable();
            $table->string('responsavel', 128)->nullable();
            $table->json('dados_adicionais')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag');
    }
};
