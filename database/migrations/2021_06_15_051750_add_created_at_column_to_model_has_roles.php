<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedAtColumnToModelHasRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('model_has_roles', 'created_at')){
            Schema::table('model_has_roles', function (Blueprint $table)
            {   
                $table->dropColumn('created_at'); //drop it
                $table->dropColumn('updated_at'); //drop it
            });
        }
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->string('created_at')->after('model_id');
            $table->string('updated_at')->after('created_at');        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            //
        });
    }
}
