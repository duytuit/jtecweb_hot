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
        Schema::create('inventory_accessories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('location_k')->comment('vị trí để linh kiện k');
            $table->string('location_c')->comment('vị trí để linh kiện c');
            $table->integer('cycle_name')->comment('kỳ');
            $table->float('warehouse_in')->comment('nhập hàng');
            $table->float('warehouse_out')->comment('xuất hàng');
            $table->float('count')->comment('số lượng');
            $table->float('unit_price')->comment('đơn giá');
            $table->float('total_amount')->comment('thành tiền');
            $table->integer('status');
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
        Schema::dropIfExists('inventory_accessories');
    }
};
