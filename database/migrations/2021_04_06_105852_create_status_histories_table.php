<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('status_histories_id')->nullable();
            $table->text('reason')->nullable();
            $table->date('date_status')->nullable();
            $table->unsignedBigInteger('personel_id')->nullable();
            $table->timestamps();

            $table->foreign('status_histories_id')
            ->references('id')
            ->on('account_status')
            ->onUpdate('cascade');

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
        Schema::dropIfExists('status_histories');
    }
}
