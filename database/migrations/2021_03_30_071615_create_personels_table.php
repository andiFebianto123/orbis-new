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
            $table->unsignedBigInteger('acc_status_id');
            $table->unsignedBigInteger('rc_dpw_id');
            $table->unsignedBigInteger('title_id');
            $table->text('first_name');
            $table->text('last_name')->nullable();
            $table->text('church_name')->nullable();
            $table->text('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('marital_status')->nullable();
            $table->text('spouse_name')->nullable();
            $table->date('spouse_date_of_birth')->nullable();
            $table->date('anniversary')->nullable();
            $table->text('child_name')->nullable();
            $table->text('ministry_background')->nullable();
            $table->text('career_background')->nullable();
            $table->string('image')->nullable();
            $table->text('street_address')->nullable();
            $table->text('city')->nullable();
            $table->text('province')->nullable();
            $table->text('postal_code')->nullable();
            $table->unsignedBigInteger('country_id');
            $table->string('email')->nullable();
            $table->string('second_email')->nullable();
            $table->text('phone')->nullable();
            $table->text('fax')->nullable();
            $table->date('first_lisenced_on')->nullable();
            $table->text('card')->nullable();
            $table->date('valid_card_start')->nullable();
            $table->date('valid_card_end')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();

            $table->foreign('acc_status_id')
            ->references('id')
            ->on('account_status')
            ->onUpdate('cascade');

            $table->foreign('rc_dpw_id')
            ->references('id')
            ->on('rc_dpwlists')
            ->onUpdate('cascade');

            $table->foreign('title_id')
            ->references('id')
            ->on('title_lists')
            ->onUpdate('cascade');

            $table->foreign('country_id')
            ->references('id')
            ->on('country_lists')
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
        Schema::dropIfExists('personels');
    }
}
