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
        Schema::table('employee_departments', function (Blueprint $table) {
            $table->integer('unit_id')->nullable()->comment('khối');
            $table->integer('team_id')->nullable()->comment('ban');
            $table->integer('process_id')->nullable()->comment('công đoạn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_departments', function (Blueprint $table) {
            //
        });
    }
};
