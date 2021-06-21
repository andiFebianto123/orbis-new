<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnToPersonels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personels', function (Blueprint $table) {
            if(Schema::hasColumn('personels', 'child_name')){
                $table->dropColumn('child_name');
            }
            if(Schema::hasColumn('personels', 'ministry_background')){
                $table->dropColumn('ministry_background');
            }
            if(Schema::hasColumn('personels', 'career_background')){
                $table->dropColumn('career_background');
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
        Schema::table('personels', function (Blueprint $table) {
            //
        });
    }
}
