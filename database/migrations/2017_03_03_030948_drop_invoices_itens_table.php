<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropInvoicesItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('invoices_itens');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('invoices_itens', function (Blueprint $table) {
            $table->increments('inv_item_id');
            $table->integer('invoice_id')->index()->unsigned();
            $table->integer('order_id')->index()->unsigned();
            $table->string('item_description', 264);
            $table->decimal('item_total', 10, 2);            
            $table->timestamps();                         
        });
    }
}
