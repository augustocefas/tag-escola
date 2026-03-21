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
        Schema::create('domain', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('domain', 255)->unique();
            $table->string('navigatio_opc', 64)->nullable();
            $table->string('navigation_subopc', 64)->nullable();
            $table->boolean('datasource')->default(true);
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
        Schema::dropIfExists('domain');
    }
};
