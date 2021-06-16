<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChurchAnnualView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS church_annual_views");
        DB::statement("CREATE VIEW church_annual_views AS 
        SELECT @row_number:=@row_number+1 as id, ct.* from(
            SELECT count(founded_on) AS total , YEAR(founded_on) AS year FROM churches, 
            (SELECT @row_number:=0) AS t
            WHERE founded_on IS NOT NULL
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
        DB::statement("DROP VIEW IF EXISTS church_annual_views");
    }
}
