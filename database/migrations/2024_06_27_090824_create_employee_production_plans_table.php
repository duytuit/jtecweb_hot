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
        Schema::create('employee_production_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->comment('mã hàng');
            $table->string('employee_id')->comment('mã nhân viên');
            $table->string('note')->comment('ghi chú');
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
        Schema::dropIfExists('employee_production_plans');
    }
};
