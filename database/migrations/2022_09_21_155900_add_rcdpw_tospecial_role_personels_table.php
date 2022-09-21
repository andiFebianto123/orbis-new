<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRcdpwTospecialRolePersonelsTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('special_role_personels', function (Blueprint $table) {
            if(!Schema::hasColumn('special_role_personels', 'rc_dpw')){
                $table->text('rc_dpw')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('special_role_personels', function (Blueprint $table) {
            $table->dropColumn('rc_dpw');
        });
    }
}
