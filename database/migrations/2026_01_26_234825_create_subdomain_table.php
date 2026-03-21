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
        Schema::create('subdomain', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('domain_id')->nullable();
            $table->foreign('domain_id')->references('id')->on('domain')->onDelete('set null');
            $table->string('subdomain', 255)->unique();
            $table->string('icon', 32)->nullable();
            $table->string('font_cor', 32)->nullable();
            $table->string('bg_cor', 32)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subdomain');
    }
};
