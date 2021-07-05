<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePressAuthorsTable extends Migration
{
    public function up()
    {
        Schema::create('press_authors', function (Blueprint $table) {
            $table->primary(['post_id','press_author_id', 'press_author_type']);
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('press_author_id');
            $table->string('press_author_type');
            
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('press_authors');
    }
}