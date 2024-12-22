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
        Schema::create('sanluong', function (Blueprint $table) {
            $table->id();
            $table->date('ngaylamviec');
            $table->integer('muctieu');
            $table->string('maylamviec');
            $table->integer('macodenv');
            $table->string('calamviec');
            $table->integer('sltrenmay');
            $table->integer('slnhanvien');
            $table->string('phantram');
            $table->string('ghichu');
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
        Schema::dropIfExists('sanluong');
    }
};
