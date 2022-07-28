<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonelsRcdpw extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personels_rcdpw', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personels_id');
            $table->unsignedBigInteger('rc_dpwlists_id');
            $table->timestamps();

            $table->foreign('personels_id')
            ->references('id')
            ->on('personels')
            ->onUpdate('cascade');

            $table->foreign('rc_dpwlists_id')
            ->references('id')
            ->on('rc_dpwlists')
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
        Schema::dropIfExists('personels_rcdpw');
    }
}
