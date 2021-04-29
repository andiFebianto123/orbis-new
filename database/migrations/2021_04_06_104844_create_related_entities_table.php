<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelatedEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('related_entities', function (Blueprint $table) {
            $table->id();
            $table->text('entity')->nullable();
            $table->text('address_entity')->nullable();
            $table->text('office_address_entity')->nullable();
            $table->integer('phone')->nullable();
            $table->text('role')->nullable();
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
        Schema::dropIfExists('related_entities');
    }
}
