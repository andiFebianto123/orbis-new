<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegalDocumentChurchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legal_document_churches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legal_document_id')->nullable();
            $table->integer('number_document')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('exp_date')->nullable();
            $table->text('status_document')->nullable();
            $table->unsignedBigInteger('churches_id')->nullable();
            $table->timestamps();

            $table->foreign('legal_document_id')
            ->references('id')
            ->on('legal_documents')
            ->onUpdate('cascade');

            $table->foreign('churches_id')
            ->references('id')
            ->on('churches')
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
        Schema::dropIfExists('legal_document_churches');
    }
}
