<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add CHECK constraint for 'status' column
        DB::statement('ALTER TABLE orders ADD CONSTRAINT check_status CHECK (status IN ("BELUM DITERIMA", "DITERIMA"))');

        // Add CHECK constraint for 'orderid' column using regular expression
        DB::statement("ALTER TABLE orders ADD CONSTRAINT check_orderid CHECK (orderid REGEXP '^OR[0-9]{5}$')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the constraints if needed
        DB::statement('ALTER TABLE orders DROP CONSTRAINT check_status');
        DB::statement('ALTER TABLE orders DROP CONSTRAINT check_orderid');
    }
};
