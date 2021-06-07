<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropStatusColumnToPersonels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personels', function (Blueprint $table){
            if(Schema::hasColumn('personels', 'acc_status_id')){
            $table->dropForeign('personels_acc_status_id_foreign');
            $table->dropColumn('acc_status_id');
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
        
    }
}
