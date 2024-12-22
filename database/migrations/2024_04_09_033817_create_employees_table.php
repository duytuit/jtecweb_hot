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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('identity_card')->comment('căn cước công dân');
            $table->string('native_land', 600)->comment('Quê quán')->nullable();
            $table->string('addresss', 600)->comment('Thường trú')->nullable();
            $table->dateTime('birthday')->nullable()->comment('năm sinh');
            $table->integer('unit_id')->nullable()->comment('khối');
            $table->integer('dept_id')->nullable()->comment('phòng');
            $table->integer('team_id')->nullable()->comment('ban');
            $table->integer('process_id')->nullable()->comment('công đoạn');
            $table->integer('status')->default(1)->comment('trạng thái hoạt động');
            $table->integer('marital')->comment('tình trạng kết hôn:0:chưa kết hôn,1:đã kết hôn,2:ly hôn');
            $table->integer('worker')->default(3)->comment('tình trạng làm việc:0:nghỉ việc,1 nghỉ chế độ bảo hiểm,2: nghỉ không lương,3: đang làm việc');
            $table->integer('positions')->default(11)->comment('chức vụ:1:General Director,2:Director,3:Supper Manager,4: Manager, 5:Supper Chief, 6:Chief, 7:Staff, 8:Suppser Leader, 9:Leader, 10:Sub Leader, 11:worker');
            $table->dateTime('begin_date_company')->nullable()->comment('ngày bắt đầu làm việc');
            $table->dateTime('end_date_company')->nullable()->comment('ngày kết thúc làm việc');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('created_by')
                ->references('id')
                ->on('admins')
                ->onDelete('cascade');
            $table->foreign('updated_by')
                ->references('id')
                ->on('admins')
                ->onDelete('cascade');
            $table->foreign('deleted_by')
                ->references('id')
                ->on('admins')
                ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
