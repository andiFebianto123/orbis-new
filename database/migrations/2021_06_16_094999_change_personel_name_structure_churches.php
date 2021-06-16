<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePersonelNameStructureChurches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('structure_churches', function (Blueprint $table){
            if(Schema::hasColumn('structure_churches', 'personel_name')){
                $table->dropColumn('personel_name');
                $table->unsignedBigInteger('personel_id')->after('churches_id')->nullable();
                $table->foreign('personel_id')
                ->references('id')
                ->on('personels')
                ->onUpdate('cascade');
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
