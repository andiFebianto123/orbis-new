<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusHistoryChurchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_history_churches', function (Blueprint $table) {
            $table->id();
            $table->text('status')->nullable();
            $table->text('reason')->nullable();
            $table->date('date_status')->nullable();
            $table->unsignedBigInteger('churches_id')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('status_history_churches');
    }
}
