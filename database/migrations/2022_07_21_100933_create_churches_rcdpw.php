<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChurchesRcdpw extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('churches_rcdpw', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('churches_id');
            $table->unsignedBigInteger('rc_dpwlists_id');
            $table->timestamps();

            $table->foreign('churches_id')
            ->references('id')
            ->on('churches')
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
        Schema::dropIfExists('churches_rcdpw');
    }
}
