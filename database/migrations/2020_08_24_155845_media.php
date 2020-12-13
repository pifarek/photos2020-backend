<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Media extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->text('filename');
            $table->enum('type', ['photo', 'youtube']);
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('order_random')->default(0);
            $table->unsignedBigInteger('total_count')->default(0);
            $table->date('taken_at');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
}
