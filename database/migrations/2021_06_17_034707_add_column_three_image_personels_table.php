<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnThreeImagePersonelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personels', function(Blueprint $table){
            if(!Schema::hasColumn('personels', 'misc_image')){
                $table->string('misc_image')->after('career_background')->nullable();
            }
            if(!Schema::hasColumn('personels', 'family_image')){
                $table->string('family_image')->after('career_background')->nullable();
            }
            if(!Schema::hasColumn('personels', 'profile_image')){
                $table->string('profile_image')->after('career_background')->nullable();
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
        //
    }
}
