<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('meta_keyword');
            $table->string('slug');

            $table->string('title');
            $table->text('content');

            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('city_id');

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('city_id')->references('id')->on('cities');

            $table->string('source_url');
            $table->boolean('parsed');

            $table->boolean('enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
