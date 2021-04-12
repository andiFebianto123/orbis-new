<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumtnToPersonalAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->string('tokenable_type')->after('id');
            $table->bigInteger('tokenable_id')->unsigned()->after('tokenable_type');
            $table->foreign('tokenable_id')->references('id')->on('personels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            
        });
    }
}
