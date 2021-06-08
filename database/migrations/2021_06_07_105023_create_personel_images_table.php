<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonelImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personel_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personel_id');
            $table->string('label');
            $table->string('image');
            $table->timestamps();

            $table->foreign('personel_id')
                ->references('id')
                ->on('personels')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personel_images');
    }
}
