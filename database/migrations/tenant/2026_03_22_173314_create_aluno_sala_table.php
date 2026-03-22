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
        Schema::create('aluno_sala', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('aluno_id')->index();
            $table->foreign('aluno_id')->references('id')->on('aluno')->onDelete('cascade');
            $table->uuid('sala_id')->index();
            $table->foreign('sala_id')->references('id')->on('sala')->onDelete('cascade');
            $table->unique(['aluno_id', 'sala_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aluno_sala');
    }
};
