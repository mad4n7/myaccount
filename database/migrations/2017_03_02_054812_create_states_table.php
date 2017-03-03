<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    
    /* SQL */
    
/*
 *
insert into us_states (code,name) values ('AL','Alabama');
insert into us_states (code,name) values ('AK','Alaska');
insert into us_states (code,name) values ('AS','American Samoa');
insert into us_states (code,name) values ('AZ','Arizona');
insert into us_states (code,name) values ('AR','Arkansas');
insert into us_states (code,name) values ('CA','California');
insert into us_states (code,name) values ('CO','Colorado');
insert into us_states (code,name) values ('CT','Connecticut');
insert into us_states (code,name) values ('DE','Delaware');
insert into us_states (code,name) values ('DC','District of Columbia');
insert into us_states (code,name) values ('FM','Federated States of Micronesia');
insert into us_states (code,name) values ('FL','Florida');
insert into us_states (code,name) values ('GA','Georgia');
insert into us_states (code,name) values ('GU','Guam');
insert into us_states (code,name) values ('HI','Hawaii');
insert into us_states (code,name) values ('ID','Idaho');
insert into us_states (code,name) values ('IL','Illinois');
insert into us_states (code,name) values ('IN','Indiana');
insert into us_states (code,name) values ('IA','Iowa');
insert into us_states (code,name) values ('KS','Kansas');
insert into us_states (code,name) values ('KY','Kentucky');
insert into us_states (code,name) values ('LA','Louisiana');
insert into us_states (code,name) values ('ME','Maine');
insert into us_states (code,name) values ('MH','Marshall Islands');
insert into us_states (code,name) values ('MD','Maryland');
insert into us_states (code,name) values ('MA','Massachusetts');
insert into us_states (code,name) values ('MI','Michigan');
insert into us_states (code,name) values ('MN','Minnesota');
insert into us_states (code,name) values ('MS','Mississippi');
insert into us_states (code,name) values ('MO','Missouri');
insert into us_states (code,name) values ('MT','Montana');
insert into us_states (code,name) values ('NE','Nebraska');
insert into us_states (code,name) values ('NV','Nevada');
insert into us_states (code,name) values ('NH','New Hampshire');
insert into us_states (code,name) values ('NJ','New Jersey');
insert into us_states (code,name) values ('NM','New Mexico');
insert into us_states (code,name) values ('NY','New York');
insert into us_states (code,name) values ('NC','North Carolina');
insert into us_states (code,name) values ('ND','North Dakota');
insert into us_states (code,name) values ('MP','Northern Mariana Islands');
insert into us_states (code,name) values ('OH','Ohio');
insert into us_states (code,name) values ('OK','Oklahoma');
insert into us_states (code,name) values ('OR','Oregon');
insert into us_states (code,name) values ('PW','Palau');
insert into us_states (code,name) values ('PA','Pennsylvania');
insert into us_states (code,name) values ('PR','Puerto Rico');
insert into us_states (code,name) values ('RI','Rhode Island');
insert into us_states (code,name) values ('SC','South Carolina');
insert into us_states (code,name) values ('SD','South Dakota');
insert into us_states (code,name) values ('TN','Tennessee');
insert into us_states (code,name) values ('TX','Texas');
insert into us_states (code,name) values ('UT','Utah');
insert into us_states (code,name) values ('VT','Vermont');
insert into us_states (code,name) values ('VI','Virgin Islands');
insert into us_states (code,name) values ('VA','Virginia');
insert into us_states (code,name) values ('WA','Washington');
insert into us_states (code,name) values ('WV','West Virginia');
insert into us_states (code,name) values ('WI','Wisconsin');
insert into us_states (code,name) values ('WY','Wyoming');
insert into us_states (code,name) values ('','Not from US');     
*/
    
    public function up()
    {
        Schema::create('us_states', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code', 2)->nullable();
            $table->string('name')->nullable();                                          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('us_states');
    }
}
