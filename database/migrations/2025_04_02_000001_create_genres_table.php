<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('genre_post', function (Blueprint $table) {
            $table->foreignId('genre_id')->constrained();
            $table->foreignId('post_id')->constrained();
            $table->primary(['genre_id', 'post_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('genre_post');
        Schema::dropIfExists('genres');
    }
};
