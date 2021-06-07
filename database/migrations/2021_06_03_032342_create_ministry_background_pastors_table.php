<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinistryBackgroundPastorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ministry_background_pastors', function (Blueprint $table) {
            $table->id();
            $table->text('ministry_title')->nullable();
            $table->text('ministry_description')->nullable();
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
        Schema::dropIfExists('ministry_background_pastors');
    }
}
