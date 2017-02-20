<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('order_id');
            $table->integer('user_id')->index()->unsigned();
            $table->integer('product_id')->index()->unsigned();
            $table->string('domain_name', 264);
            $table->string('domain_user', 264);
            $table->string('domain_password', 164);
            $table->date('next_duedate');
            $table->timestamps();            
        });
        
        /*
        Schema::table('orders', function (Blueprint $table) {
           $table->engine = "InnoDB";
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        }); 
             */   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
