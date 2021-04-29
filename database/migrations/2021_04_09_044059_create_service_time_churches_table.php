<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTimeChurchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_time_churches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_type_id')->nullable();
            $table->datetime('service_time')->nullable();
            $table->string('service_room')->nullable();
            $table->unsignedBigInteger('churches_id')->nullable();
            $table->timestamps();

            $table->foreign('service_type_id')
            ->references('id')
            ->on('service_types')
            ->onUpdate('cascade');

            $table->foreign('churches_id')
            ->references('id')
            ->on('churches')
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
        Schema::dropIfExists('service_time_churches');
    }
}
