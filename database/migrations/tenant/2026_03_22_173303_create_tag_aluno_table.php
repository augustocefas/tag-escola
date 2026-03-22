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
        Schema::create('tag_aluno', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('aluno_id')->index();
            $table->foreign('aluno_id')->references('id')->on('aluno')->onDelete('cascade');
            $table->uuid('tag_id')->index();
            $table->foreign('tag_id')->references('id')->on('tag')->onDelete('cascade');
            $table->unique(['aluno_id', 'tag_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_aluno');
    }
};
