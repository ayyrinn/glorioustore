<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('onlinetransactions', function (Blueprint $table) {
            $table->char('transactionid', 10)->primary();
            $table->char('courierid', 5)->nullable();
            $table->enum('status', ['BELUM BAYAR', 'DIKEMAS', 'DIKIRIM', 'DITERIMA'])->default('BELUM BAYAR');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('transactionid')->references('transactionid')->on('transactions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kuririd')->references('employeeid')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onlinetransactions');
    }
};
