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
        Schema::create('check_cutting_machine', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        if (!Schema::hasTable('check_cutting_machine')) {
            Schema::create('check_cutting_machine', function (Blueprint $table) {
                $table->id();

                // $table->integer('code');
                // $table->string('name')->comment('tên');
                // $table->integer('status');

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
        Schema::dropIfExists('check_cutting_machine');
    }
};
