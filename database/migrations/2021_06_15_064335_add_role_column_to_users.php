<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleColumnToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        if (Schema::hasColumn('users', 'role_id')) //check the column
        {
            Schema::table('users', function (Blueprint $table)
            {   
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id'); //drop it
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->after('status_user')->default('1');
            
            $table->foreign('role_id')
            ->references('id')
            ->on('roles')
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
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
