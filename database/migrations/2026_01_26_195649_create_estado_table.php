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
        Schema::create('estado', function (Blueprint $table) {
            $table->id()->unsigned()->primary();
            $table->string('nome', 128)->nullable();
            $table->string('uf', 2)->nullable();
            $table->integer('ibge')->unsigned()->nullable();
            $table->bigInteger('pais_id')->unsigned()->nullable();
            $table->foreign('pais_id')->references('id')->on('pais');
            $table->string('ddd', 64)->nullable();
            $table->timestamps();
        });
        $sql = file_get_contents(database_path('sql/02_estado.sql'));
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado');
    }
};
