<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no')->unique();
            $table->foreignId('firm_id')->constrained('firms');
            $table->foreignId('bill_type_id')->constrained('bill_types');
            $table->foreignId('party_id')->constrained('parties');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->string('location_id')->nullable();
            $table->timestamp('sale_date')->nullable();
            $table->integer('days')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->double('ex_rate')->default(0);
            $table->double('total')->default(0);
            $table->double('cgst')->default(0);
            $table->double('sgst')->default(0);
            $table->double('igst')->default(0);
            $table->double('grand_total')->default(0);
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
        Schema::dropIfExists('sales');
    }
}
