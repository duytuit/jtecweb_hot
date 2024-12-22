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
        Schema::create('requireds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_required');
            $table->string('code');
            $table->float('quantity')->comment('số lượng sản phẩm');
            $table->string('unit_price', 20)->comment('đơn vị');
            $table->longText('content')->nullable()->comment('Nội dung yêu cầu');
            $table->integer('size')->comment('kích thước');
            $table->string('image', 600)->comment('hình ảnh')->nullable();
            $table->string('required_department_id')->comment('bộ phận yêu cầu');
            $table->string('receiving_department_ids')->comment('bộ phận tiếp nhận');
            $table->integer('status');
            $table->integer('from_type')->comment('Loại công việc');
            $table->dateTime('date_completed')->nullable()->comment('Ngày hoàn thành công việc');
            $table->integer('order');
            $table->integer('created_by')->nullable()->comment('Người yêu cầu');
            $table->integer('completed_by')->nullable()->comment('Người thực hiện');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('requireds');
    }
};
