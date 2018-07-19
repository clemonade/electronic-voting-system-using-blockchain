<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisteredVotersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registeredvoters', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 100)->index();
            $table->char('nric', 12)->unique();
            $table->char('gender', 1)->index();
            /*$table->char('locality', 5)->index();
            $table->char('votingDistrict', 5)->index();*/
            $table->char('federalconstituency', 5)->index();
            $table->char('stateconstituency', 5)->index();
            $table->char('state', 2)->index();
            $table->boolean('valid')->default(true);
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
        Schema::dropIfExists('registeredVoters');
    }
}
