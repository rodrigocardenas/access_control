<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessLogTable extends Migration
{
    public function up()
    {
        Schema::create('access_log', function (Blueprint $table) {

		$table->id();
		$table->bigInteger('user_id')->unsigned();
		$table->bigInteger('building_id')->unsigned();
		$table->string('block',1000);
		$table->datetime('date');
        $table->bigInteger('type')->unsigned();
        $table->timestamps();
        $table->timestamp('deleted_at')->nullable();

        

        });
    }

    public function down()
    {
        Schema::dropIfExists('access_log');
    }
}