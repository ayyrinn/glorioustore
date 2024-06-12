<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderdetails', function (Blueprint $table) {
            $table->char('orderid', 7);
            $table->char('productid', 7)->charset('utf8mb4')->collation('utf8mb4_general_ci');
            $table->integer('qty');
            $table->integer('buying_price');
            $table->integer('subtotal');
            $table->timestamps();

            // Add foreign key constraint for 'orderid'
            $table->foreign('orderid')->references('orderid')->on('orders')->onDelete('cascade');

            // Add foreign key constraint for 'productid'
            $table->foreign('productid')->references('productid')->on('products')->onDelete('cascade');

            // Add composite index for 'orderid' and 'productid'
            $table->index(['orderid', 'productid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orderdetails');
    }
}
