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
        Schema::create('production_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->comment('mã hàng');
            $table->string('lot_no')->comment('mã lot');
            $table->longText('description')->nullable()->comment('thông tin kế hoạch sản xuất');
            $table->text('note')->comment('ghi chú');
            $table->softDeletes();
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
        Schema::dropIfExists('production_plans');
    }
};
