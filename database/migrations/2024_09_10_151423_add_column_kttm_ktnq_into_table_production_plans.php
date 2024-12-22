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
        Schema::table('production_plans', function (Blueprint $table) {
            $table->text('kttm')->comment('Kiểm tra thông mạch');
            $table->text('ktnq')->comment('Kiểm tra ngoại quan');
            $table->text('cam')->comment('công đoạn cắm');
            $table->text('dap1')->comment('công đoạn dập 1');
            $table->text('dap2')->comment('công đoạn dập 2');
            $table->text('cat')->comment('công đoạn cắt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_plans', function (Blueprint $table) {
            //
        });
    }
};
