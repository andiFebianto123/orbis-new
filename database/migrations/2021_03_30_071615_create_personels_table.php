<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personels', function (Blueprint $table) {
            $table->id();
            $table->integer('acc_status_id');
            $table->integer('rc_dpw_id');
            $table->integer('title_id');
            $table->text('first_name');
            $table->text('last_name');
            $table->text('gender');
            $table->date('date_of_birth');
            $table->text('marital_status');
            $table->text('spouse_name')->nullable();
            $table->date('spouse_date_of_birth')->nullable();
            $table->date('anniversary')->nullable();
            $table->text('child_name')->nullable();
            $table->text('ministry_background');
            $table->text('career_background');
            $table->string('image')->nullable();
            $table->text('street_address');
            $table->text('city');
            $table->text('province');
            $table->text('postal_code');
            $table->integer('country_id');
            $table->string('first_email');
            $table->string('second_email')->nullable();
            $table->integer('phone');
            $table->integer('fax');
            $table->date('first_lisenced_on');
            $table->text('card');
            $table->date('valid_card_start');
            $table->date('valid_card_end');
            $table->string('password');
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
        Schema::dropIfExists('personels');
    }
}
