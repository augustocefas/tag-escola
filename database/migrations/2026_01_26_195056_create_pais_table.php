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
        Schema::create('pais', function (Blueprint $table) {
            $table->id()->unsigned()->primary();
            $table->string('nome', 64)->nullable();
            $table->string('nome_pt', 64)->nullable();
            $table->string('sigla', 2)->nullable();
            $table->integer('bacen')->unsigned()->nullable();
            $table->timestamps();
        });
        //get sql file in ../sql/pais.sql and run it
        $sql = file_get_contents(database_path('sql/01_pais.sql'));
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pais');
    }
};
