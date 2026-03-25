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
        Schema::create('financeiro_mov', function (Blueprint $table) {
            $table->id();
            $table->uuid('financeiro_id')->index();
            $table->foreign('financeiro_id')->references('id')->on('financeiros')->onDelete('cascade');
            $table->uuid('tag_id')->index()->nullable();
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->integer('qt');
            $table->string('descricao')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financeiro_mov');
    }
};
