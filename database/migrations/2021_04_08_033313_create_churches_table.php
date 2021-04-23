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
            $table->date('founded_on')->nullable();
            $table->integer('church_id')->nullable();
            $table->integer('church_type_id')->nullable();
            $table->integer('rc_dpw_id');
            $table->text('church_name');
            $table->text('lead_pastor_name');
            $table->text('contact_person');
            $table->text('building_name')->nullable();
            $table->text('church_address');
            $table->text('office_address');
            $table->text('city');
            $table->text('province');
            $table->text('postal_code');
            $table->integer('country_id')->nullable();
            $table->string('first_email');
            $table->string('second_email')->nullable();
            $table->text('phone');
            $table->text('fax');
            $table->text('website')->nullable();
            $table->text('map_url')->nullable();
            $table->text('service_time_church');
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
