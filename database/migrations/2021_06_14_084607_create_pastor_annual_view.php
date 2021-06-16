<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePastorAnnualView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS pastor_annual_views");
        DB::statement("CREATE VIEW pastor_annual_views AS 
        SELECT func_inc_var_session() as id, ct.* FROM(
            SELECT count(first_licensed_on) AS total , YEAR(first_licensed_on) AS year FROM personels 
            WHERE first_licensed_on IS NOT NULL
            GROUP BY year)
        as ct");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS pastor_annual_views");
    }
}
