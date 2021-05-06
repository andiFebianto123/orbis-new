<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnToChurches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('churches', function (Blueprint $table) {
            $table->text('lead_pastor_name')->nullable()->change();
            $table->text('contact_person')->nullable()->change();
            $table->text('church_address')->nullable()->change();
            $table->text('office_address')->nullable()->change();
            $table->text('office_address')->nullable()->change();
            $table->text('city')->nullable()->change();
            $table->text('province')->nullable()->change();
            $table->text('postal_code')->nullable()->change();
            $table->text('phone')->nullable()->change();
            $table->text('fax')->nullable()->change();
            $table->text('service_time_church')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('churches', function (Blueprint $table) {
            //
        });
    }
}
