<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationInvoicesItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices_itens', function (Blueprint $table) {
            
            $table->foreign('invoice_id')
                ->references('invoice_id')->on('invoices')
                ->onDelete('cascade')
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
        Schema::table('invoices', function (Blueprint $table) {           
           $table->dropForeign('invoices_itens_invoice_id_foreign');
        });
    }
}
