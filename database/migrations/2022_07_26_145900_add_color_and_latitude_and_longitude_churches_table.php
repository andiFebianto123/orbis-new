<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColorAndLatitudeAndLongitudeChurchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('churches', function (Blueprint $table) {
            if(!Schema::hasColumn('churches', 'task_color')){
                $table->string('task_color')->nullable()->after('date_of_certificate');
            }
            if(!Schema::hasColumn('churches', 'latitude')){
                $table->string('latitude')->nullable()->after('task_color');
            }
            if(!Schema::hasColumn('churches', 'longitude')){
                $table->string('longitude')->nullable()->after('latitude');
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
        Schema::table('churches', function (Blueprint $table) {
            $table->dropColumn('task_color');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
}
