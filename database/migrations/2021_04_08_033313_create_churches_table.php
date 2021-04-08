<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChurchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('churches', function (Blueprint $table) {
            $table->id();
            $table->text('church_status');
            $table->date('founded_on');
            $table->integer('church_id');
            $table->integer('church_type_id');
            $table->integer('rc_dpw_id');
            $table->text('church_name');
            $table->text('contact_person');
            $table->text('building_name');
            $table->text('church_address');
            $table->text('office_address');
            $table->text('city');
            $table->text('province');
            $table->text('postal_code');
            $table->integer('country_id');
            $table->string('first_email');
            $table->string('second_email')->nullable();
            $table->integer('phone');
            $table->integer('fax');
            $table->text('website')->nullable();
            $table->text('map_url')->nullable();
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
        Schema::dropIfExists('churches');
    }
}
