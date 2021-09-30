<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogHubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_hubs', function (Blueprint $table) {
            $table->id();
            $table->string('personel_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('ip')->nullable();
            $table->text('action')->nullable();
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
        Schema::dropIfExists('log_hubs');
    }
}
