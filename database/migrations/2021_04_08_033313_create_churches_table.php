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
            $table->unsignedBigInteger('church_type_id')->nullable();
            $table->unsignedBigInteger('rc_dpw_id');
            $table->text('church_name');
            $table->text('lead_pastor_name');
            $table->text('contact_person');
            $table->text('building_name')->nullable();
            $table->text('church_address');
            $table->text('office_address');
            $table->text('city');
            $table->text('province');
            $table->text('postal_code');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('first_email')->unique();
            $table->string('second_email')->nullable();
            $table->text('phone');
            $table->text('fax');
            $table->text('website')->nullable();
            $table->text('map_url')->nullable();
            $table->text('service_time_church');
            $table->timestamps();

            $table->foreign('church_type_id')
            ->references('id')
            ->on('church_types')
            ->onUpdate('cascade');

            $table->foreign('rc_dpw_id')
            ->references('id')
            ->on('rc_dpwlists')
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
        Schema::dropIfExists('churches');
    }
}
