<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_receives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('firm_id')->constrained('firms');
            $table->foreignId('party_id')->constrained('parties');
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->timestamp('sale_date')->nullable();
            $table->timestamp('date')->nullable();
            $table->foreignId('payment_type_id')->constrained('payment_types');
            $table->foreignId('expense_account_id')->constrained('expense_accounts');
            $table->double('discount')->default(0);
            $table->double('amount')->default(0);
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('payment_recieves');
    }
}
