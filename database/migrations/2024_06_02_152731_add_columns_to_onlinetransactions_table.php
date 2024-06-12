<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOnlineTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('onlinetransactions', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('status');
            $table->string('proofpayment')->nullable()->after('notes');
            $table->string('proofdelivery')->nullable()->after('proofpayment');
        });
    }

    public function down()
    {
        Schema::table('onlinetransactions', function (Blueprint $table) {
            $table->dropColumn(['notes', 'proofpayment', 'proofdelivery']);
        });
    }
}

