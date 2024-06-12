<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the table
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('transactionid')->primary();
            $table->unsignedBigInteger('customerid');
            $table->unsignedBigInteger('employeeid');
            $table->date('date');
            $table->decimal('payment', 8, 2);
            $table->string('type');
            $table->decimal('total', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('customerid')->references('customerid')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('employeeid')->references('employeeid')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });

        // Add check constraints separately
        DB::statement("ALTER TABLE transactions ADD CONSTRAINT check_payment CHECK (payment IN ('CASH', 'DEBIT', 'OTHER'))");
        DB::statement("ALTER TABLE transactions ADD CONSTRAINT check_transactionid CHECK (transactionid REGEXP '^TR[0-9]{8}$')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
