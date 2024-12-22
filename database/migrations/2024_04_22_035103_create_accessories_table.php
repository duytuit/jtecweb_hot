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
        Schema::create('accessories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('location_k')->comment('vị trí để linh kiện k');
            $table->string('location_c')->comment('vị trí để linh kiện c');
            $table->string('location')->comment('vị trí để linh kiện');
            $table->integer('material_norms')->comment('định mức linh kiện');
            $table->string('image',600)->comment('hình ảnh')->nullable();
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
        Schema::dropIfExists('accessories');
    }
};
