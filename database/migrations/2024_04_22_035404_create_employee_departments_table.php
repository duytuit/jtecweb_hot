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
        if (!Schema::hasTable('employee_departments')) {
            Schema::create('employee_departments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('employee_id');
                $table->integer('department_id');
                $table->integer('positions')->comment('1:trưởng phòng,2:phó phòng,3:trợ lý,0:nhân viên');
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->integer('deleted_by')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_departments');
    }
};
