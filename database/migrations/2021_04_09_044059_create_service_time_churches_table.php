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
            $table->integer('service_type_id')->nullable();
            $table->datetime('service_time')->nullable();
            $table->string('service_room')->nullable();
            $table->integer('churches_id')->nullable();
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
        Schema::dropIfExists('service_time_churches');
    }
}
