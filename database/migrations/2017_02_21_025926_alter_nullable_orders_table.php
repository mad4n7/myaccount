<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNullableOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('domain_name', 264)->nullable()->change();
            $table->string('domain_user', 264)->nullable()->change();
            $table->string('domain_password', 164)->nullable()->change();
            $table->date('next_duedate')->nullable()->change();
            $table->string('periodicity', 46)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('domain_name', 264)->change();
            $table->string('domain_user', 264)->change();
            $table->string('domain_password', 164)->change();
            $table->date('next_duedate')->change();
            $table->string('periodicity', 46)->change();
        });
    }
}
