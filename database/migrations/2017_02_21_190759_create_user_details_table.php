<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('user_details', function (Blueprint $table) {
            $table->increments('user_details_id');
            $table->integer('user_id')->index()->unsigned();   
            $table->integer('country_id')->index()->unsigned()->nullable();
            $table->string('phone_number', 64)->nullable();
            $table->string('address', 234)->nullable();
            $table->string('address2', 234)->nullable();
            $table->string('city', 234)->nullable();
            $table->string('zip_code', 34)->nullable();
            $table->string('activation_token', 264)->nullable();
            $table->char('activated', 1)->nullable()->comment('1 = activated / 0 = inactive');            
            $table->timestamps();
           
        });
        
        Schema::table('user_details', function (Blueprint $table) {
        $table->foreign('user_id')
            ->references('id')->on('users')
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
        Schema::dropIfExists('user_details');
    }
}
