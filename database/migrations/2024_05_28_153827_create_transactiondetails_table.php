<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactiondetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactiondetails', function (Blueprint $table) {
            $table->char('transactionid', 10);
            $table->char('productid', 7);
            $table->integer('quantity')->unsigned();
            $table->integer('price')->nullable();
            $table->integer('total')->nullable();

            $table->foreign('transactionid')
                ->references('transactionid')
                ->on('transactions')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('productid')
                ->references('productid')
                ->on('products')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->primary(['transactionid', 'productid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactiondetails');
    }
}
