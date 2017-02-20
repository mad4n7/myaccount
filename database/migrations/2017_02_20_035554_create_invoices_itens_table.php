<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices_itens');
    }
}
