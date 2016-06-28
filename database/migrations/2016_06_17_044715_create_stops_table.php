<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('stops', function (Blueprint $table) {
            $table->string('stop_id');
            $table->string('stop_name');
            $table->string('stop_desc');
            $table->string('stop_lat');
            $table->string('stop_lon');
            $table->string('zone_id');
            $table->string('stop_url');
            $table->string('stop_code');
            $table->string('stop_street');
            $table->string('stop_city');
            $table->string('stop_region');
            $table->string('stop_postcode');
            $table->string('stop_country');
            $table->string('location_type');
            $table->string('parent_station');
            $table->string('stop_timezone');
            $table->string('wheelchair_boarding');
            $table->string('direction');
            $table->string('position');
            $table->string('the_geom');
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
        //
        Schema::drop('stops');
    }
}
