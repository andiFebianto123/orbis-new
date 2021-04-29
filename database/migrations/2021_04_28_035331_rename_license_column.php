<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameLicenseColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personels', function (Blueprint $table) {
            $table->renameColumn('first_lisenced_on', 'first_licensed_on');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personels', function (Blueprint $table) {
            $table->renameColumn('first_licensed_on', 'first_lisenced_on');
        });
    }
}
