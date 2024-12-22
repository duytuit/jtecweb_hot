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
        Schema::create('upload_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->comment('mã hàng');
            $table->string('url')->comment('đường dẫn');
            $table->string('type')->comment('loại màn hình');
            $table->string('created_by')->comment('người tạo');
            $table->string('updated_by')->comment('người cập nhật');
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
        Schema::dropIfExists('upload_data');
    }
};
