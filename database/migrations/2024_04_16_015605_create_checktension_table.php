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
        Schema::create('checktension', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->comment('Mã code nhân viên');
            $table->float('target125')->comment('Giá trị đối chiếu Chủng loại B - 1.25');
            $table->float('target2')->comment('Giá trị đối chiếu Chủng loại B - 2');
            $table->float('target55')->comment('Giá trị đối chiếu Chủng loại B - 5.5');
            $table->float('weight125')->comment('Nhập vào giá trị Chủng loại B - 1.25');
            $table->float('weight2')->comment('Nhập vào giá trị Chủng loại B - 2');
            $table->float('weight55')->comment('Nhập vào giá trị Chủng loại B - 5.5');
            $table->string('selectComputer')->comment('Chọn máy kiểm tra');
            $table->string('checkresult')->comment('Kết quả kiểm tra');
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
        Schema::dropIfExists('checktension');
    }
};
