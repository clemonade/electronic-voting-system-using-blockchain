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
        Schema::create('registeredVoters', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 100)->index();
            $table->char('nric', 12)->unique();
            $table->char('gender', 1)->index();
            $table->char('locality', 5)->index();
            $table->char('votingDistrict', 5)->index();
            $table->char('federalConstituency', 5)->index();
            $table->char('stateConstituency', 5)->index();
            $table->char('state', 2)->index();
            $table->boolean('valid');
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
