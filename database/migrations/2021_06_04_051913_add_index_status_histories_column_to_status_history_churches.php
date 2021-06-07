<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexStatusHistoriesColumnToStatusHistoryChurches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('status_history_churches', function(Blueprint $table){
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('status_history_churches');
            if(!array_key_exists("status_histories_churches_date_index", $indexesFound)){
                $table->index(['churches_id', 'date_status'], 'status_histories_churches_date_index');
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
