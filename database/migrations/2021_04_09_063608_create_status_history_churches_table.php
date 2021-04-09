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
        Schema::dropIfExists('status_history_churches');
    }
}
