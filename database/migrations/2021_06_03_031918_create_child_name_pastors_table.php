<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildNamePastorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_name_pastors', function (Blueprint $table) {
            $table->id();
            $table->text('child_name')->nullable();
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
        Schema::dropIfExists('child_name_pastors');
    }
}
