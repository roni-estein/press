<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePressTagsTable extends Migration
{
    public function up()
    {
        Schema::create('press_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('text')->index();
            $table->string('slug')->unique()->index();
            
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('press_tags');
    }
}