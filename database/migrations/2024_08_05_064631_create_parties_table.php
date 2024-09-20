<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('party_name')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('reference_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('office_no')->nullable();
            $table->text('address')->nullable();
            $table->string('state')->nullable();
            $table->string('state_code')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('gst_tin')->nullable();
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
        Schema::dropIfExists('parties');
    }
}
