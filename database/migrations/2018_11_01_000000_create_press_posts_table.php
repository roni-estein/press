<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePressPostsTable extends Migration
{
    public function up()
    {
        Schema::create('press_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identifier')->index();
            $table->string('slug')->unique()->index();
            $table->string('title');
            $table->string('description');
            $table->text('body');
            $table->text('extra')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('press_posts');
    }
}