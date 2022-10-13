<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('status_histories', function (Blueprint $table) {
            if(!Schema::hasColumn('status_histories', 'status')){
                $table->text('status')->nullable()->after('status_histories_id');
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
        Schema::table('status_histories', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
