<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryLogToLogHubs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_hubs', function (Blueprint $table) {
            if(!Schema::hasColumn('log_hubs', 'category')){
                $table->string('category')->after('email')->nullable();
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
        Schema::table('log_hubs', function (Blueprint $table) {
            //
        });
    }
}
