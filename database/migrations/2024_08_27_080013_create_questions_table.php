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
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',500)->comment('tiêu đề');
            $table->string('description',1000)->comment('mô tả');
            $table->integer('parent_id');
            $table->text('content_form')->comment('Nội dung');
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
        Schema::dropIfExists('questions');
    }
};
