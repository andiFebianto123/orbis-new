<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialRolePersonelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_role_personels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('special_role_id')->nullable();
            $table->integer('personel_id')->nullable();
            $table->timestamps();
            $table->foreign('special_role_id')
            ->references('id')
            ->on('special_roles')
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
        Schema::dropIfExists('special_role_personels');
    }
}
