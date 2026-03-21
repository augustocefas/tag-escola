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
       Schema::create('anexo', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('filename', 64);
            $table->string('extension', 8);
            $table->string('mime', 64)->nullable();
            $table->integer('size')->nullable();
            $table->string('original_name', 64)->nullable();
            $table->uuid('users_id')->nullable();
            $table->foreign('users_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anexo');
    }
};
