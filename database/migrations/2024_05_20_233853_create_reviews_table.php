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
        Schema::create('reviews', function(Blueprint $table)
        {
            $table->id();
            //$table->unsignedBigInteger('book_id');
            $table->string('review');
            $table->unsignedTinyInteger('rating');
            $table->timestamps();
            //$table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');  // Con esta sintaxis, se puede especificar cuales son los campos exactos que se deben utilizar para la relación, la siguiente línea es la forma abreviada que hace lo mismo

            $table->foreignId('book_id')
                  ->constrained()
                  ->cascadeOnDelete(); //Por convención en Laravel, si se define 'nombre_tabla'_id como singular, laravel asocia automáticamente, de lo contrario, tanto en hasMany como en belongsTo se debe especificar el foreign key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
