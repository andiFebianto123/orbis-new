<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStructureChurchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('structure_churches', function (Blueprint $table) {
            $table->id();
            $table->text('personel_name')->nullable();
            $table->unsignedBigInteger('title_structure_id')->nullable();
            $table->unsignedBigInteger('churches_id')->nullable();
            $table->timestamps();

            $table->foreign('title_structure_id')
            ->references('id')
            ->on('ministry_roles')
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
        Schema::dropIfExists('structure_churches');
    }
}
