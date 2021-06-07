<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerBackgroundPastorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('career_background_pastors', function (Blueprint $table) {
            $table->id();
            $table->text('career_title')->nullable();
            $table->text('career_description')->nullable();
            $table->unsignedBigInteger('personel_id')->nullable();
            $table->timestamps();

            $table->foreign('personel_id')
            ->references('id')
            ->on('personels')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('career_background_pastors');
    }
}
