<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requireds', function (Blueprint $table) {
            $table->text('confirm_form')->nullable()->commen('Form xác nhận');
            $table->float('remaining')->nullable()->commen('Số lượng còn lại');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requireds', function (Blueprint $table) {
            //
        });
    }
};
