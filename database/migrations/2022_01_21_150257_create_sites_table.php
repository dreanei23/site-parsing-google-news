<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('domain')->unique();
            $table->string('selector_category')->nullable();
            $table->string('selector_city')->nullable();
            $table->string('selector_title')->nullable();
            $table->string('selector_content')->nullable();
            $table->string('selector_image')->nullable();
            $table->string('selector_image_Label')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
    }
}
